<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\User;
use AppBundle\Entity\Address;
use AppBundle\Entity\Product;
use AppBundle\Entity\Book;
use AppBundle\Entity\ProductBook;
use AppBundle\Entity\Delivery;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Doctrine\ORM\EntityRepository;

class HomeController extends Controller
{
    

    /**
     * @Route("/test/{var}/{var2}/{var3}/{user_id}", defaults={"var" = " ", "var2" = " ", "var3" = " ", "user_id" = " "}, name="gender")
     * @Method("POST|GET")
     * @Template("mine/test.html.twig")
     */
    public function genderAction($var, $var2, $var3, $user_id)
    {   
        if($var2==" " && $var3==" "){
            $repository = $this->getDoctrine()->getRepository('AppBundle:Product');
            $query = $repository->createQueryBuilder('p')
            ->select('p.name', 'p.id', 'p.price')
            ->groupBy('p.name')
            ->getQuery();

            $entities = $query->getResult();
        }
        else if($var3!==" "){
            $repository = $this->getDoctrine()->getRepository('AppBundle:Product');
            $query = $repository->createQueryBuilder('p')
            ->select('p.name', 'p.id', 'p.price')
            ->groupBy('p.name')
            ->orderBy('p.price' , $var3)
            ->getQuery();

        $entities = $query->getResult();
        }
            else {
            $repository = $this->getDoctrine()->getRepository('AppBundle:Product');
            $query = $repository->createQueryBuilder('p')
            ->select('p.name', 'p.id', 'p.price')
            ->where("p.gender='$var2'")
            ->groupBy('p.name')
            ->getQuery();

        $entities = $query->getResult();
        }
      
        return $this->render('mine/test.html.twig', array( 'my_product' => $entities, 'variable'=>$var, 'user_id'=>$user_id, ));
    } 

    /**
     * @Route("/test/{variable}/{user_id}", defaults={"variable" = " ", "user_id" = " "}, name="test")
     * @Method("POST|GET")
     * @Template("mine/test.html.twig")
     */
    public function testAction($variable, $user_id)
    { 
        $product = $this->getDoctrine()
        ->getRepository('AppBundle:Product')
        ->findAll();

        return $this->render('mine/test.html.twig', array( 'my_product' => $product, 'variable'=>$variable, 'user_id'=>$user_id ));
    } 

    /**
     * @Route("/book/{var}/{user_id}/{price}/{p_id}/{product_name}/{deliveryId}", defaults={"var" = " ", "user_id" = " ", "price" = " ", "product_name" = " ", "p_id" = " ", "deliveryId" = " "}, name="book")
     * @Method("POST|GET")
     * @Template("mine/book.html.twig")
     */
    public function bookAction(Request $request, $var, $user_id, $price, $p_id, $product_name, $deliveryId)
    {
        $delivery = $this->getDoctrine()
        ->getRepository('AppBundle:Delivery')
        ->findAll();

        $book = new Book();
        $product_book = new ProductBook();

        $d= new \DateTime("now");

        if($request->getMethod()=='POST')
        {
           
            $amount=$request->get('amount');
            $delId=$request->get('delId');
            if($amount>1){
                $price = $price*$amount;
            }
            foreach ($delivery as $key) {
               if($delId == $key->getId()){
                    $price = $price + $key->getCost(); 
                }
            }
            $book->setUserId($user_id);
            $book->setDeliveryId($delId);
            $book->setCost($price);
            $book->setBookDate($d);

            $em = $this->getDoctrine()->getManager();

            $em->persist($book);
            $em->flush();

            $id = $book->getId();
            $deliveryId = $book->getDeliveryId();

            $product_book->setBookId($id);
            $product_book->setProductId($p_id);
            $product_book->setAmount($amount);

            $em = $this->getDoctrine()->getManager();

            $em->persist($product_book);
            $em->flush();
        }

        return $this->render('mine/book.html.twig', array( 'variable'=>$var, 'user_id'=>$user_id, 'price'=>$price, 'p_id'=>$p_id, 'product_name' => $product_name, 'deliveryId'=>$deliveryId));
    }

  /**
     * @Route("/product/{var}/{user_id}/{price}/{p_id}/{product_name}", defaults={"var" = " ", "user_id" = " ", "price" = " ", "product_name" = " ", "p_id" = " "}, name="product")
     * @Method("POST|GET")
     * @Template("mine/product.html.twig")
     */
    public function productAction($var, $user_id, $price, $p_id, $product_name)
    {
        return $this->render('mine/product.html.twig', array( 'variable'=>$var, 'user_id'=>$user_id, 'price'=>$price, 'p_id' => $p_id, 'product_name'=>$product_name));
    }

    /**
     * @Route("login", name="login")
     */

     public function signinAction(Request $request)
    {

        $em=$this->getDoctrine()->getManager();
        $repository =$em->getRepository('AppBundle:User');

        if($request->getMethod()=='POST')
        {
            $login=$request->get('login');
            $password=$request->get('password');

            $user=$repository->findOneBy(array('login'=>$login,'password'=>$password));

            if ($user) //if user has values
                return $this->render('mine/login.html.twig', array('user' => $user));

            else//if login is incorrect
                return $this->render('mine/login.html.twig');
        }

        return $this->render('mine/login.html.twig');

    }


   
    /**
     * @Route("register", name="register")
     */
    public function registerAction(Request $request)
    {
        // create a task and give it some dummy data for this example
    $data = array(
        'person'  => new User(),
        'location' => new Address(),
    );
        
        $form = $this->createFormBuilder($data)
            ->add('firstName', TextType::class)
            ->add('lastName', TextType::class)
            ->add('login', TextType::class)
            ->add('password', TextType::class)
            ->add('phone', TextType::class)
            ->add('street', TextType::class)
            ->add('houseNumber', TextType::class)
            ->add('flatNumber', TextType::class)
            ->add('city', TextType::class)
            ->add('postalCode', TextType::class)  
            ->add('Register', SubmitType::class, array('label' => 'Register'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $firstname=$form['firstName']->getData(); 
            $lastName=$form['lastName']->getData(); 
            $login=$form['login']->getData(); 
            $password=$form['password']->getData(); 
            $phone=$form['phone']->getData(); 
            $street=$form['street']->getData(); 
            $houseNumber=$form['houseNumber']->getData(); 
            $flatNumber=$form['flatNumber']->getData(); 
            $city=$form['city']->getData(); 
            $postalCode=$form['postalCode']->getData(); 

            $data['location']->setStreet($street);
            $data['location']->setHouseNumber($houseNumber);
            $data['location']->setFlatNumber($flatNumber);
            $data['location']->setCity($city);
            $data['location']->setPostalCode($postalCode);

            $em = $this->getDoctrine()->getManager();

            // tells Doctrine you want to (eventually) save the Product (no queries yet)
            
            $em->persist($data['location']);
            $em->flush();
            $id = $data['location']->getId();


            $data['person']->setFirstName($firstname);
            $data['person']->setLastName($lastName);
            $data['person']->setLogin($login);
            $data['person']->setPassword($password);
            $data['person']->setAddressId($id);
            $data['person']->setPhone($phone);
            


            $em = $this->getDoctrine()->getManager();

            // tells Doctrine you want to (eventually) save the Product (no queries yet)
            $em->persist($data['person']);

            // actually executes the queries (i.e. the INSERT query)
            $em->flush();
        }
        
            // return $this->redirectToRoute('task_success');

            return $this->render('mine/register.html.twig', array(
                'form' => $form->createView(),
            ));
        
    }

     



    // public function createAction(Request $request)
    // {
        

    //  $product = new Product;
        
    //     $form = $this->createFormBuilder($data)
    //         ->setAction($this->generateUrl('about'))
    //         ->setMethod('POST')
    //         ->add('name', TextType::class)
    //         ->add('price', TextType::class)
    //         ->add('gender', TextType::class)
    //         ->add('subcategoryId', TextType::class)
    //         ->add('image', TextType::class)  
    //         ->add('Register', SubmitType::class, array('label' => 'Create Post'))
    //         ->getForm();

    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $name=$form['name']->getData(); 
    //         $price=$form['price']->getData(); 
    //         $gender=$form['gender']->getData(); 
    //         $subcategoryId=$form['subcategoryId']->getData(); 
    //         $image=$form['image']->getData();
           

    //         $product->setStreet($name);
    //         $product->setHouseNumber($price);
    //         $product->setFlatNumber($gender);
    //         $product->setCity($city);
    //         $product->setPostalCode($subcategoryId);
    //         $product->setPostalCode($image);

    //         $em = $this->getDoctrine()->getManager();

    //         /// tells Doctrine you want to (eventually) save the Product (no queries yet)
            
    //         $em->persist($product);
    //         $em->flush();

          
    //     }
        
    //         // return $this->redirectToRoute('task_success');

    //         return $this->render('mine/product.html.twig', array(
    //             'form' => $form->createView(),
    //         ));
        
    // }   

}
