<?php

namespace App\Command;

use App\Entity\Product;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class InitializeDataCommand extends Command
{
    protected static $defaultName = 'app:initialize-data';

    private $entityManager;
    private $passwordHasher;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;

        parent::__construct();
    }

    protected function configure()
{
    $this->setName('app:initialize-data')
        ->setDescription('Ajoute des produits à la base de données et crée un utilisateur administrateur.');
}


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $products = [
            ['name' => 'Blackbelt', 'price' => 29.90, 'image' => '1.jpeg'],
            ['name' => 'BlueBelt', 'price' => 29.90, 'image' => '2.jpeg'],
            ['name' => 'Street', 'price' => 34.50, 'image' => '3.jpeg'],
            ['name' => 'Pokeball', 'price' => 45.00, 'image' => '4.jpeg'],
            ['name' => 'PinkLady', 'price' => 29.90, 'image' => '5.jpeg'],
            ['name' => 'Snow', 'price' => 32.00, 'image' => '6.jpeg'],
            ['name' => 'Greyback', 'price' => 28.50, 'image' => '7.jpeg'],
            ['name' => 'BlueCloud', 'price' => 45.00, 'image' => '8.jpeg'],
            ['name' => 'BornInUsa', 'price' => 59.90, 'image' => '9.jpeg'],
            ['name' => 'GreenSchool', 'price' => 42.20, 'image' => '10.jpeg'],
        ];

        foreach ($products as $data) {
            $product = new Product();
            $product->setName($data['name']);
            $product->setPrice($data['price']);
            $product->setImage($data['image']);
            $product->setStockXS(2);
            $product->setStockS(2);
            $product->setStockM(2);
            $product->setStockL(2);
            $product->setStockXL(2);

            $this->entityManager->persist($product);
        }

        $adminUser = new User();
        $adminUser->setUsername('admin');
        $adminUser->setEmail('admin@example.com');
        $adminUser->setRoles(['ROLE_ADMIN', 'ROLE_USER']);
        $encodedPassword = $this->passwordHasher->hashPassword($adminUser, 'admin_password');
        $adminUser->setPassword($encodedPassword);
        $adminUser->setAddress('123 Main Street');


        $this->entityManager->persist($adminUser);

        $this->entityManager->flush();

        $output->writeln('Les produits et l\'utilisateur administrateur ont été ajoutés avec succès.');

        return Command::SUCCESS;
    }
}
