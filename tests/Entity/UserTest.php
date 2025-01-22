<?php

namespace App\Tests\Entity;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

final class UserTest extends  TestCase
{
    public function testGetId()
    {
        $user = new User();
        $this->assertNull($user->getId());
    }

    public function testGetAvatar()
    {
        $user = new User();
        $avatar = 'avatar.png';
        $user->setAvatar($avatar);
        $this->assertEquals($avatar, $user->getAvatar());
    }

    public function testGetFirstname()
    {
        $user = new User();
        $firstname = 'John';
        $user->setFirstname($firstname);
        $this->assertEquals($firstname, $user->getFirstname());
    }

    public function testGetLastname()
    {
        $user = new User();
        $lastname = 'Doe';
        $user->setLastname($lastname);
        $this->assertEquals($lastname, $user->getLastname());
    }

    public function testGetEmail()
    {
        $user = new User();
        $email = 'john.doe@example.com';
        $user->setEmail($email);
        $this->assertEquals($email, $user->getEmail());
    }

    public function testGetPassword()
    {
        $user = new User();
        $password = 'password123';
        $user->setPassword($password);
        $this->assertEquals($password, $user->getPassword());
    }

    public function testGetProfession()
    {
        $user = new User();
        $profession = 'Developer';
        $user->setProfession($profession);
        $this->assertEquals($profession, $user->getProfession());
    }

    public function testGetLocation()
    {
        $user = new User();
        $location = 'Paris';
        $user->setLocation($location);
        $this->assertEquals($location, $user->getLocation());
    }

    public function testGetBiography()
    {
        $user = new User();
        $biography = 'Lorem ipsum dolor sit amet.';
        $user->setBiography($biography);
        $this->assertEquals($biography, $user->getBiography());
    }

    public function testGetRoles()
    {
        $user = new User();
        $roles = ['ROLE_USER', 'ROLE_ADMIN'];
        $user->setRoles($roles);
        $this->assertEquals($roles, $user->getRoles());
    }

    public function testGetCertificate()
    {
        $user = new User();
        $certificate = 'Certificate of Excellence';
        $user->setCertificate($certificate);
        $this->assertEquals($certificate, $user->getCertificate());
    }

    public function testGetCreatedAt()
    {
        $user = new User();
        $createdAt = new \DateTimeImmutable();
        $user->setCreatedAt($createdAt);
        $this->assertEquals($createdAt, $user->getCreatedAt());
    }

    public function testIsVerified()
    {
        $user = new User();
        $user->setVerified(true);
        $this->assertTrue($user->isVerified());
    }

    public function testGetUserIdentifier()
    {
        $user = new User();
        $email = 'john.doe@example.com';
        $user->setEmail($email);
        $this->assertEquals($email, $user->getUserIdentifier());
    }

    public function testIsCertificateIsValidate()
    {
        $user = new User();
        $user->setCertificateIsValidate(true);
        $this->assertTrue($user->isCertificateIsValidate());
    }

}
