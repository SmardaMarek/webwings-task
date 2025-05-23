<?php
require 'AuthenticaService.php';
require 'FileClass.php';

$auth = new AuthenticaService();
$file = new FileClass();

$productId = $auth->createProduct([
    "name" => "Test product",
    "declarationName" => "Test product declaration",
    "skus" => [
    "sku-3012"
    ],
]);

$orderId = $auth->createOrder(
    [
        "externalId" => "external-ccx-3",
        "carrierId" => 7,
        "branchId" => null,
        "price" => "153.00",
        "priceCurrency" => "CZK",
        "cod" => false,
        "codValue" => null,
        "codValueCurrency" => null,
        "vs" => null,
        "companyName" => "Test s.r.o.",
        "firstName" => null,
        "lastName" => null,
        "addressLine1" => "Adresa 11",
        "addressLine2" => "2nd floor",
        "addressLine3" => null,
        "city" => "Brno",
        "zip" => "602 00",
        "country" => "CZ",
        "state" => null,
        "phone" => "+420000000000",
        "email" => "test@test.cz",
        "processingDate" => date('Y-m-d'),
        "printDeliveryNote" => true,
        "orderNumber" => null,
        "orderTags" => [
            "tag1",
            "tag2"
        ],
        "packagingInstructions" => [
            [
                "message" => "Apply fragile sticker",
                "packagingInstructionType" => "after"
            ]
        ],
        "items" => [
            [
                "productId" => $productId,
                "amount" => 1
            ]
        ]
    ]
);

$auth->uploadOrderInvoice($orderId, [
    "name" => "test.pdf",
    "content" => $file->getPdfBase64("test.pdf")
]);

echo "Order created - success \n";
