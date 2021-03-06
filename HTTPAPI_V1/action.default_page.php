<?php
/*
 * @author Anakeen
 * @license http://www.fsf.org/licensing/licenses/agpl-3.0.html GNU Affero General Public License
 * @package FDL
*/
function default_page(Action &$action) {

    $usage = new ActionUsage($action);

    $usage->setStrictMode(false);
    $usage->verify(true);
    $crud = json_decode(\ApplicationParameterManager::getParameterValue("HTTPAPI_V1", "CRUD_CLASS"), true);

    $baseURL = \Dcp\HttpApi\V1\Api\AnalyzeURL::getBaseURL();

    usort($crud, function ($value1, $value2) {
        return $value1["canonicalURL"] > $value2["canonicalURL"];
    });

    $defaultValues = function($value) use ($baseURL) {
        $value["canonicalURL"] = isset($value["canonicalURL"]) ? $value["canonicalURL"] : $value["regExp"];
        $value["canonicalURL"] = $baseURL. $value["canonicalURL"];
        $value["description"] = isset($value["description"]) ? $value["description"] : $value["class"];
        return $value;
    };

    $crud = array_map($defaultValues, $crud);

    $action->lay->esetBlockData('SYSTEM_CRUD', $crud);
    $action->lay->set("DOCUMENTATION_URL", \ApplicationParameterManager::getParameterValue(ApplicationParameterManager::CURRENT_APPLICATION, "DOCUMENTATION_URL"));

}