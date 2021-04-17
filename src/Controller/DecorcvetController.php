<?php

namespace App\Controller;

use App\Entity\User;
use App\Gallery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Category;
use App\Entity\Products;
use App\Entity\Status;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class DecorcvetController extends AbstractController
{
    /**
     * @Route("/dekorcvet", name="dekorcvet")
     */
    public function index(Request $request)
    {
        $productArr = [];
        $pic = null;
        $edit = false;
        $uploadsDir = $this->makeDir('../uploads/');
        $imagesDir = $this->makeDir('../public/images/');
        $thumbDir = $this->makeDir('../public/images/thumbs/');

        $categoryRepo = $this->getDoctrine()->getRepository(Category::class)->findAll();
        $statusRepo = $this->getDoctrine()->getRepository(Status::class)->findAll();
        $productsRepo = $this->getDoctrine()->getRepository(Products::class)->findAll();

        foreach ($categoryRepo as $caty) {
            $category[$caty->getName()] = $caty->getId();
        }
        foreach ($statusRepo as $caty) {
            $status[$caty->getStatus()] = $caty->getId();
        }
        foreach ($productsRepo as $caty) {
            $products[$caty->getId()] = $caty->getHead();
        }
        $idProd = $request->query->get('id');
        $produ = $this->getDoctrine()->getRepository(Products::class)->findOneBy(['id' => $idProd]);
        if (!empty($idProd) && !empty($produ)) {
            $idd = $idProd;
            $productArr = [
                'head' => $produ->getHead(),
                'price' => $produ->getPrice(),
                'category' => $produ->getCategory(),
                'status' => $produ->getStatus(),
                'description' => $produ->getDescription(),
                'cultiv' => $produ->getCultiv(),
                'useFor' => $produ->getUsefor(),
            ];
            if (!empty($produ->getPic())) {
                $pic = '/images/thumbs/' . $produ->getPic();
            }
            $edit = true;
        }

        $form = $this->createFormBuilder($productArr)
            ->add('head', TextType::class, ['required' => true, 'label' => 'Заглавие'])
            ->add('price', NumberType::class, ['required' => false, 'label' => 'Цена'])
            ->add('category', ChoiceType::class, ['required' => true, 'choices' => $category, 'label' => 'Категория'])
            ->add('status', ChoiceType::class, ['required' => true, 'choices' => $status, 'label' => 'Статус'])
            ->add('description', TextareaType::class, ['required' => false, 'label' => 'Описание'])
            ->add('cultiv', TextareaType::class, ['required' => false, 'label' => 'Отглеждане'])
            ->add('useFor', TextareaType::class, ['required' => false, 'label' => 'Използване'])
            ->add('pic', FileType::class, ['required' => false, 'label' => 'Снимка'])
            ->add('save', SubmitType::class, ['label' => 'Запис',])
            ->getForm();

        foreach ($products as $k => $item) {
            $formProduct[$item . ' (' . $k . ')'] = $k;
        }
        $form1 = $this->createFormBuilder()
            ->add('product', ChoiceType::class, ['required' => true, 'choices' => array_merge(['НОВО РАСТЕНИЕ' => null], $formProduct), 'label' => 'Продукт'])
            ->add('save', SubmitType::class, ['label' => 'Промяна',])
            ->getForm();
        $form1->handleRequest($request);
        if ($form1->isSubmitted() && $form1->isValid()) {
            $id = ($form1->getData())['product'];
            if (empty($id)) {
                return $this->redirectToRoute('dekorcvet');
            }
            return $this->redirectToRoute('dekorcvet', ['id' => $id]);
        }


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            if (!empty($idd)) {
                $product = $this->getDoctrine()->getRepository(Products::class)->find($idd);
            } else {
                $product = new Products();
            }

            $file = $data['pic'];
            if (!empty($file)) {
                $fileName = rand(10000, 9999999) . '.' . $file->guessExtension();
                $file->move($uploadsDir, $fileName);
                Gallery::smart_resize_image(
                    $uploadsDir . $fileName,
                    0,
                    250,
                    true,
                    $thumbDir . $fileName);
                Gallery::smart_resize_image(
                    $uploadsDir . $fileName,
                    0,
                    820,
                    true,
                    $imagesDir . $fileName);
                $product->setPic($fileName);
            }
            $product
                ->setHead(trim($data['head']))
                ->setPrice($data['price'])
                ->setCategory($data['category'])
                ->setStatus($data['status'])
                ->setDescription(trim($data['description']))
                ->setCultiv(trim($data['cultiv']))
                ->setUsefor(trim($data['useFor']));
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('dekorcvet');
        }

        return $this->render('decorcvet/index.html.twig', [
            'form' => $form->createView(),
            'form1' => $form1->createView(),
            'pic' => $pic,
            'edit' => $edit,
        ]);
    }


    /**
     * @Route("/dekorcvet/changepass", name="change_pass")
     */
    public function changePass(UserPasswordEncoderInterface $passwordEncoder, Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => ['label' => 'Нова парола'],
                'second_options' => ['label' => 'Повторете паролата'],
                'constraints' => [
                    new NotBlank(['message' => 'Моля, въведете парола',]),
                    new Length([
                        'min' => 4,
                        'minMessage' => 'Паролата трябва да бъде поне {{ limit }} символа',
                        'max' => 4096,
                    ]),
                ],
            ])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $newPass = $form->getData();
            $usrId = $this->getUser()->getId();
            $user = $this->getDoctrine()->getRepository(User::class)->find($usrId);
            $user->setPassword($passwordEncoder->encodePassword($user, $newPass['password']));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
//            return $this->redirectToRoute('dekorcvet');
            return $this->redirectToRoute('app_logout');
        }

        return $this->render('security/change-pass.html.twig', [
            'form' => $form->createView(),
        ]);


    }


    /**
     * @Route("/dekorcvet/galleries-update", name="updateGallery")
     */
    public function updateGallery()
    {
        return $this->render('decorcvet/galleryUpdate.html.twig');
    }


    /**
     * @Route("/dekorcvet/make-gallery/{galleryName}", name="makeGallery")
     */
    public function makeGallery(string $galleryName)
    {
        $uploadsDir = $this->makeDir('../uploads/' . $galleryName . '/');
        $imagesDir = $this->makeDir('../public/galleries/' . $galleryName);
        $thumbDir = $this->makeDir($imagesDir . '/thumb/');

        // delete all files
        $filesInImg = glob($imagesDir . '*');
        foreach ($filesInImg as $imgFile) {
            if (is_file($imgFile)) {
                unlink($imgFile);
            }
        }
        $filesInImg = glob($thumbDir . '*');
        foreach ($filesInImg as $imgFile) {
            if (is_file($imgFile)) {
                unlink($imgFile);
            }
        }

        $files = scandir($uploadsDir);
        foreach ($files as $k => $file) {
            if (
                is_file($uploadsDir . $file)
                && !is_dir($imagesDir . $file)
                && strpos(mime_content_type($uploadsDir . $file), 'image') !== false
            ) {

                Gallery::smart_resize_image(
                    $uploadsDir . $file,
                    0,
                    250,
                    true,
                    $thumbDir . $file);
                Gallery::smart_resize_image(
                    $uploadsDir . $file,
                    0,
                    820,
                    true,
                    $imagesDir . $file);
            }
        }
        $this->addFlash('success', $galleryName . ' се опресни успешно.');
        return $this->redirectToRoute('updateGallery');
    }

    private function makeDir($dir): string
    {
        if (!file_exists($dir) || !is_dir($dir)) {
            mkdir($dir);
        }
        return realpath($dir) . '/';

    }
}
