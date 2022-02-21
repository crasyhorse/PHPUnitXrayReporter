# PHP-Unit Xray Reporter [![Release](https://img.shields.io/github/v/release/crasyhorse/PHPUnitXrayReporter)](https://github.com/crasyhorse/PHPUnitXrayReporter/releases/latest) [![Downloads](https://img.shields.io/github/downloads/crasyhorse/PHPUnitXrayReporter/total)](https://github.com/crasyhorse/PHPUnitXrayReporter)


The purpose of this package is to convert annotated information from PHPunit tests doc blocks to JSON files. These are then used to import or update test executions in XRAY - Jira. It take use of the amazing package [Jasny PHPDoc parser](https://github.com/jasny/phpdoc-parser#jasny-phpdoc-parser).

## Covered XRAY-fields and how to fill them

<table>
    <tr>
        <th>XRAY-JSON-field </th><th> Annotation usage </th><th> Behavior</th>
    </tr>
    <tr>
        <td>testExecutionKey </td><td> @XRAY-testExecutionKey EXAMPLEKEY-1 </td><td> Important field, if test execution is to update. It is obtained from the configuration file or the corresponding annotation. The annotation is prioritized higher than the configuration file.</td>
    </tr>
    <tr>
        <th colspan=3>INFO object (just needed, if testExecutionKey is not given in config or doc block for some tests)</th>
    </tr>
    <tr>
        <td>project </td><td> / </td><td> The short name of the project in Jira. Without testExecutionKey mandatory (otherwise exception). Is obtained from the configuration file. If it is not included, an attempt is made to generate it from either the testExecutionKey or testKey.</td>
    </tr>
    <tr>
        <td>summary </td><td> / </td><td> Is taken from the config file</td>
    </tr>
    <tr>
        <td>description </td><td> / </td><td> "</td>
    </tr>
    <tr>
        <td>version </td><td> / </td><td> "</td>
    </tr>
    <tr>
        <td>revision </td><td> / </td><td> "</td>
    </tr>
    <tr>
        <td>user </td><td> / </td><td> "</td>
    </tr>
    <tr>
        <td>testPlanKey </td><td> / </td><td> "</td>
    </tr>
    <tr>
        <td>testEnvironments </td><td> / </td><td> "</td>
    </tr>
    <tr>
        <th colspan=3>TESTS object (array of test information in the corresponding testExecution)</th>
    </tr>
    <tr>
        <td>testKey </td><td> @XRAY-TESTS-testKey EXAMPLEKEY-1 </td><td> Important if you want to update an existing test. Is only taken from the corresponding annotation of the doc block.</td>
    </tr>
    <tr>
        <td>testInfo </td><td> / </td><td> Separate object under tests</td>
    </tr>
    <tr>
        <td>start </td><td> / </td><td> Automatically taken from the PHPunit report</td>
    </tr>
    <tr>
        <td>finish </td><td> / </td><td> "</td>
    </tr>
    <tr>
        <td>comment </td><td> / </td><td> Automatically taken from the PHPunit report if the test failed, otherwise a static string.</td>
    </tr>
    <tr>
        <td>status </td><td> / </td><td> Automatically generated (PASS|FAIL|TODO)</td>
    </tr>
    <tr>
        <td>defects </td><td> @XRAY-TESTS-defects DEFECT1,DEFECT2,... </td><td> Can only be set with the annotation in the doc block comment.</td>
    </tr>
    <tr>
        <th style='text-align: center' colspan=3>TESTINFO object (part of tests).</th>
    </tr>
    <tr>
        <td>projectKey</td><td>@XRAY-TESTINFO-projectKey EXAMPLEKEY-1</td><td>If annotation is given, it takes it from there. Otherwise it will be generated either from project, testExecutionKey or testKey</td>
    </tr>
    <tr>
        <td>summary</td><td>summary on first line</td><td>It's the first doc block line of the test without annotation. If such line doesn't exist, the PHPunit test-method name is taken.</td>
    </tr>
    <tr>
        <td>description</td><td>the summary + following lines without tag</td><td>It's taken from the summary plus the following lines after the summary without annotation. If nothing is given, it does not appear in the file. </td>
    </tr>
    <tr>
        <td>testType</td><td>@XRAY-TESTINFO-testType Generic</td><td>One of Manual, Cucumber ore Generic. If annotation is not given, Generic is the default.</td>
    </tr>
    <tr>
        <td>requirementKeys</td><td>@XRAY-TESTINFO-requirementKeys EXAMPLEKEY-1,EXAMPLEKEY-2,...</td><td>Just taken from the annotation.</td>
    </tr>
    <tr>
        <td>labels</td><td>@XRAY-TESTINFO-labels lable-1,label2,...</td><td>"</td>
    </tr>
    <tr>
        <td>definition</td><td>@XRAY-TESTINFO-definition definition text</td><td>This field defines the test and should be unique! According to the XRAY documentation, this makes it an important component if the testKey is not specified. By the definition the text can be assigned namely to an already existing test, or on basis of this field a completely new test can be created. The field is obtained like the summary from the annotation or otherwise the test name.</td>
    </tr>
</table>

### Example

This example uses each possible tag.

```php
   /**
     * Successful test.
     * This test will return a PASS result and has all possible annotations we implemented.
     *
     * @test
     * @XRAY-testExecutionKey PHPUnitXrayReporter-1
     *
     * @XRAY-TESTS-testKey PHPUnitXrayReporter-2
     * @XRAY-TESTS-comment This Test should return PASS
     * @XRAY-TESTS-defects PHPUnitXrayReporter-1,PHPUnitXrayReporter-2
     *
     * @XRAY-TESTINFO-projectKey PHPUnitXrayReporter
     * @XRAY-TESTINFO-testType Generic
     * @XRAY-TESTINFO-requirementKeys PHPUnitXrayReporter-1,PHPUnitXrayReporter-2
     * @XRAY-TESTINFO-labels workInProgress,Bug,NeedsTriage
     * @XRAY-TESTINFO-definition The Test sums 2+2=4 and expects 4
     */
    public function fully_annotated_successful_test(): void
    {
        $asset = new Asset();
        $expected = 4;
        $actual = $asset->add(2, 2);
        $this->assertEquals($expected, $actual);
    } 
```

## Configuration

### Phpunit XML

Add this inside your Phpunit Configurationfile

```xml
   <extensions>
        <extension class="Crasyhorse\PhpunitXrayReporter\Extension">
            <arguments>
                <string>.\tests\XRAYFiles</string>
                <string>.\xray-reporterrc.json</string>
            </arguments>
        </extension>
    </extensions>
```



The first argument is needed and defines the directory to store the generated json files. 
The second one ist optional. It defines the path to the configuration file for the reporter. The default path is **xray-reporterrc.json**.

### Example xray-reporterrc.json 

```json
{
    "testExecutionKey": "DEMO-1",
    "info": {
        "project": "DEMO",
        "summary": "Example Config",
        "description": "This is an example of the description in the config file.",
        "version": "0.1.0",
        "revision": "0.1.0.0002",
        "user": "DemoUser",
        "testPlanKey": "DEMO-2",
        "testEnvironments": [
            "IOS", "Android"
        ]
    }
}
```

The Fields summary, description, version, revision, user. testPlanKey and testEnvironments can be left empty.
Ideally one of project or testExecution should be given

