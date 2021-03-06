<?php
/*
 * @author Anakeen
 * @license http://www.fsf.org/licensing/licenses/agpl-3.0.html GNU Affero General Public License
 * @package FDL
*/

namespace Dcp\Pu\HttpApi\V1\Test\Families;

use Dcp\HttpApi\V1\Api\AnalyzeURL;
use Dcp\HttpApi\V1\Crud\Exception as DocumentException;
use Dcp\HttpApi\V1\DocManager\DocManager;
use Dcp\HttpApi\V1\Crud\Enumerates as Enumerates;
use Dcp\Pu\HttpApi\V1\Test\Documents\TestDocumentCrud;

require_once 'HTTPAPI_V1_UNITTEST/PU_TestCaseApi.php';

class TestFamilyEnumerateCrud extends TestDocumentCrud
{
    /**
     * Test that unable to create document
     *
     * @dataProvider dataCreateDocument
     */
    public function testCreate()
    {
        $crud = new Enumerates();
        $crud->setUrlParameters(array(
            "familyId" => "TST_APIBASE"
        ));
        try {
            $crud->create();
            $this->assertFalse(true, "An exception must occur");
        }
        catch(DocumentException $exception) {
            $this->assertEquals(501, $exception->getHttpStatus());
        }
    }
    
    public function dataCreateDocument()
    {
        return array(
            array(
                "NO"
            )
        );
    }
    /**
     * @param string $name
     * @param array $expectedData
     * @throws DocumentException
     * @dataProvider dataReadDocument
     */
    public function testRead($name, $fields, $expectedData)
    {
        
        $crud = new Enumerates();
        $crud->setUrlParameters(array(
            "familyId" => "TST_APIBASE"
        ));
        $data = $crud->read($name);
        
        $data = json_decode(json_encode($data) , true);
        
        $expectedData = $this->prepareData($expectedData);
        $this->verifyData($data, $expectedData);
    }
    
    public function dataReadDocument()
    {
        return array(
            array(
                "",
                null,
                file_get_contents("HTTPAPI_V1_UNITTEST/families/enumerates/enumerates.json")
            ) ,
            array(
                "tst_apibase_enum_array",
                null,
                file_get_contents("HTTPAPI_V1_UNITTEST/families/enumerates/enumerate_tst_apibase_enum_array.json")
            )
        );
    }
    /**
     * Test that unable to update document
     *
     * @dataProvider dataUpdateDocument
     * @param string $name
     * @param array $updateValues
     * @param array $expectedValues
     */
    public function testUpdateDocument($name, $updateValues, $expectedValues)
    {
        $crud = new Enumerates();
        $crud->setUrlParameters(array(
            "familyId" => $name
        ));
        try {
            $crud->update($name);
            $this->assertFalse(true, "An exception must occur");
        }
        catch(DocumentException $exception) {
            $this->assertEquals(501, $exception->getHttpStatus());
        }
    }
    
    public function dataUpdateDocument()
    {
        return array(
            array(
                "TST_APIBASE",
                null,
                array()
            )
        );
    }
    /**
     * Test that unable to update document
     *
     * @dataProvider dataDeleteDocument
     * @param string $name
     * @param string $fields
     * @param array $expectedValues
     */
    public function testDeleteDocument($name, $fields, $expectedValues)
    {
        $crud = new Enumerates();
        $crud->setUrlParameters(array(
            "familyId" => $name
        ));
        try {
            $crud->delete(null);
            $this->assertFalse(true, "An exception must occur");
        }
        catch(DocumentException $exception) {
            $this->assertEquals(501, $exception->getHttpStatus());
        }
    }
    
    public function dataDeleteDocument()
    {
        return array(
            array(
                "TST_APIBASE",
                null,
                array()
            )
        );
    }
    
    public function prepareData($data)
    {
        //Get RefDoc
        $familyDoc = DocManager::getDocument("TST_APIBASE");
        $this->assertNotNull($familyDoc, "Unable to find family TST_APIBASE doc");
        //Replace variant part
        $data = str_replace('%baseURL%', AnalyzeURL::getBaseURL() , $data);
        $data = str_replace('%initId%', $familyDoc->getPropertyValue('initid') , $data);
        $data = str_replace('%id%', $familyDoc->getPropertyValue('id') , $data);
        
        $data = json_decode($data, true);
        
        $this->assertEquals(JSON_ERROR_NONE, json_last_error() , "Unable to decode the test data");
        
        return $data;
    }
}
