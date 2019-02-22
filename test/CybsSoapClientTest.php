<?php

use PHPUnit\Framework\TestCase;

class CybsSoapClientTestCase extends TestCase
{
    public function testClientCreationWithLocalFile()
    {
        $properties = parse_ini_file('cybs.ini');
        $this->assertNotEquals(false, $properties);
        $client = new CybsSoapClient();
        $this->assertEquals(
            $properties['merchant_id'],
            $client->getMerchantId()
        );
        $this->assertEquals(
            $properties['transaction_key'],
            $client->getTransactionKey()
        );        
    }

    public function testClientCreationWithPropertiesArray()
    {
        $properties = [
            'merchant_id' => 'your_merchant_id',
            'transaction_key' => 'your_transaction_key',
            'wsdl' => 'https://ics2wstest.ic3.com/commerce/1.x/transactionProcessor/CyberSourceTransaction_1.120.wsdl',
            'nvp_wsdl' => 'https://ics2wstest.ic3.com/commerce/1.x/transactionProcessor/CyberSourceTransaction_NVP_1.120.wsdl',
        ];

        $this->assertNotEquals(false, $properties);
        $client = new CybsSoapClient([], $properties);
        $this->assertEquals(
            $properties['merchant_id'],
            $client->getMerchantId()
        );
        $this->assertEquals(
            $properties['transaction_key'],
            $client->getTransactionKey()
        );
    }

    public function testBasicPaymentWithArray()
    {
        $properties = [
            'merchant_id' => 'ndx1520',
            'transaction_key' => 'I6prb/NoWpi4R1T5NyTCLVvFrBDM5Q/d5081s+t8ghDQBHHEWy91e6TZaCYZ9KvT153Vx4beLUADXRdvhPq/dZy84yyWk2L7yssQa+2kk5DBrWGRDHB+FJXfhcGs+Mw0Uiaoaw9C3P3NXcPdDlRLHtP2WSH2sfReEutW97ls35gZ1dK/6/wS9aNwcyqTrsQziL3rxLbPBaipdmtiz5FPRi5M/5HHInU+rH0fRukCKJOqT13hsfxthdBxqJKdgW3eE5PVr6lbYC4cuO6uDDoLToYwajGYX9N6jykQ4YyruWekhjEikvtHf/lt0lcmwSYZB2SLAPpbBI02FfcE+741pw==',
            'wsdl' => 'https://ics2wstest.ic3.com/commerce/1.x/transactionProcessor/CyberSourceTransaction_1.120.wsdl',
            'nvp_wsdl' => 'https://ics2wstest.ic3.com/commerce/1.x/transactionProcessor/CyberSourceTransaction_NVP_1.120.wsdl',
        ];

        $this->assertNotEquals(false, $properties);
        $client = new CybsSoapClient([], $properties);
        $request = $client->createRequest('ndx1520');

        $ccAuthService = new stdClass();
        $ccAuthService->run = 'true';
        $request->ccAuthService = $ccAuthService;

        $ccCaptureService = new stdClass();
        $ccCaptureService->run = 'true';
        $request->ccCaptureService = $ccCaptureService;

        $billTo = new stdClass();
        $billTo->firstName = 'John';
        $billTo->lastName = 'Doe';
        $billTo->street1 = '1295 Charleston Road';
        $billTo->city = 'Mountain View';
        $billTo->state = 'CA';
        $billTo->postalCode = '94043';
        $billTo->country = 'US';
        $billTo->email = 'null@cybersource.com';
        $billTo->ipAddress = '10.7.111.111';
        $request->billTo = $billTo;

        $card = new stdClass();
        $card->accountNumber = '4111111111111111';
        $card->expirationMonth = '12';
        $card->expirationYear = '2020';
        $request->card = $card;

        $purchaseTotals = new stdClass();
        $purchaseTotals->currency = 'USD';
        $purchaseTotals->grandTotalAmount = '90.01';
        $request->purchaseTotals = $purchaseTotals;

        //$request->ics_applications = 'ics_ecp_debit';

// Populate $request here with other necessary properties
        $reply = $client->__soapCall('runTransaction', [$request]);
        print_r($reply);
    }
}
