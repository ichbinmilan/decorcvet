<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Category;
use App\Entity\Products;
use App\Entity\Status;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class DecorcvetController extends AbstractController
{
    /**
     * @Route("/decorcvet", name="decorcvet")
     */
    public function index(Request $request)
    {
        $categoryRepo = $this->getDoctrine()->getRepository(Category::class)->findAll();
        $statusRepo = $this->getDoctrine()->getRepository(Status::class)->findAll();
        $productsRepo = $this->getDoctrine()->getRepository(Products::class)->findAll();

        foreach ($categoryRepo as $caty) {
            $category[$caty->getName()] = $caty->getId();
        }
        foreach ($statusRepo as $caty) {
            $status[$caty->getStatus()] = $caty->getId();
        }

        $form = $this->createFormBuilder()
            ->add('head', TextType::class, ['required' => false,])
            ->add('price', NumberType::class, ['required' => false,])
            ->add('category', ChoiceType::class, ['required' => false, 'choices' => $category,])
            ->add('status', ChoiceType::class, ['required' => false, 'choices' => $status,])
            ->add('description', TextareaType::class, ['required' => false,])
            ->add('cultiv', TextareaType::class, ['required' => false,])
            ->add('useFor', TextareaType::class, ['required' => false,])
            ->add('pic', FileType::class, ['required' => false,])
            ->add('save', SubmitType::class, ['label' => 'Запис',])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $file = $data['pic'];
            $uploadsDir = $this->makeDir('../uploads/');
            $imagesDir = $this->makeDir('../public/images/');
            $thumbDir = $this->makeDir('../public/images/thumbs/');

            $fileName = rand(10000, 9999999) . '.' . $file->guessExtension();
            $file->move($uploadsDir, $fileName);
            $product = new Products();
            $product
                ->setHead(trim($data['head']))
                ->setPrice($data['price'])
                ->setCategory($data['category'])
                ->setStatus($data['status'])
                ->setDescription(trim($data['description']))
                ->setCultiv(trim($data['cultiv']))
                ->setUsefor(trim($data['useFor']))
                ->setPic($fileName);
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            $this->smart_resize_image($uploadsDir . $fileName,
                $height = 150,
                $output = $thumbDir . $fileName);
            $this->smart_resize_image($uploadsDir . $fileName,
                $height = 820,
                $output = $imagesDir . $fileName);

            return $this->redirectToRoute('decorcvet');
        }

        return $this->render('decorcvet/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    private function makeDir($dir)
    {
        var_dump($dir);
        if (!file_exists($dir) || !is_dir($dir)) {
            mkdir($dir);
        }
        return realpath($dir) . '/';

    }

    private function smart_resize_image($file,
                                        $width = 0,
                                        $height = 0,
                                        $proportional = true,
                                        $output = 'file',
                                        $delete_original = false,
                                        $use_linux_commands = false)
    {

        if ($height <= 0 && $width <= 0) return false;
        # Setting defaults and meta
        $info = getimagesize($file);
        $image = '';
        $final_width = 0;
        $final_height = 0;
        list($width_old, $height_old) = $info;
        # Calculating proportionality
        if ($proportional) {
            if ($width == 0) $factor = $height / $height_old;
            elseif ($height == 0) $factor = $width / $width_old;
            else                    $factor = min($width / $width_old, $height / $height_old);
            $final_width = round($width_old * $factor);
            $final_height = round($height_old * $factor);
        } else {
            $final_width = ($width <= 0) ? $width_old : $width;
            $final_height = ($height <= 0) ? $height_old : $height;
        }
        # Loading image to memory according to type
        switch ($info[2]) {
            case IMAGETYPE_GIF:
                $image = imagecreatefromgif($file);
                break;
            case IMAGETYPE_JPEG:
                $image = imagecreatefromjpeg($file);
                break;
            case IMAGETYPE_PNG:
                $image = imagecreatefrompng($file);
                break;
            default:
                return false;
        }


        # This is the resizing/resampling/transparency-preserving magic
        $image_resized = imagecreatetruecolor($final_width, $final_height);
        if (($info[2] == IMAGETYPE_GIF) || ($info[2] == IMAGETYPE_PNG)) {
            $transparency = imagecolortransparent($image);
            if ($transparency >= 0) {
                $transparent_color = imagecolorsforindex($image, $trnprt_indx);
                $transparency = imagecolorallocate($image_resized, $trnprt_color['red'], $trnprt_color['green'], $trnprt_color['blue']);
                imagefill($image_resized, 0, 0, $transparency);
                imagecolortransparent($image_resized, $transparency);
            } elseif ($info[2] == IMAGETYPE_PNG) {
                imagealphablending($image_resized, false);
                $color = imagecolorallocatealpha($image_resized, 0, 0, 0, 127);
                imagefill($image_resized, 0, 0, $color);
                imagesavealpha($image_resized, true);
            }
        }
        imagecopyresampled($image_resized, $image, 0, 0, 0, 0, $final_width, $final_height, $width_old, $height_old);

        # Taking care of original, if needed
        if ($delete_original) {
            if ($use_linux_commands) exec('rm ' . $file);
            else @unlink($file);
        }
        # Preparing a method of providing result
        switch (strtolower($output)) {
            case 'browser':
                $mime = image_type_to_mime_type($info[2]);
                header("Content-type: $mime");
                $output = NULL;
                break;
            case 'file':
                $output = $file;
                break;
            case 'return':
                return $image_resized;
                break;
            default:
                break;
        }

        # Writing image according to type to the output destination
        switch ($info[2]) {
            case IMAGETYPE_GIF:
                imagegif($image_resized, $output);
                break;
            case IMAGETYPE_JPEG:
                imagejpeg($image_resized, $output);
                break;
            case IMAGETYPE_PNG:
                imagepng($image_resized, $output);
                break;
            default:
                return false;
        }
        return true;
    }

}
