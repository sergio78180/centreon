<?php

/*
 * Copyright 2015 Centreon (http://www.centreon.com/)
 * 
 * Centreon is a full-fledged industry-strength solution that meets 
 * the needs in IT infrastructure and application monitoring for 
 * service performance.
 * 
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 *    http://www.apache.org/licenses/LICENSE-2.0  
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * 
 * For more information : contact@centreon.com
 * 
 */

namespace Centreon\Internal;

use Centreon\Internal\Di;
use Centreon\Internal\Utils\CommandLine\Colorize;
use Centreon\Internal\Module\Informations;
use GetOptionKit\Argument;
use GetOptionKit\OptionCollection;
use GetOptionKit\OptionParser;
use GetOptionKit\OptionResult;
use GetOptionKit\OptionPrinter\ConsoleOptionPrinter;
use Centreon\Events\ManageCommandOptions as ManageCommandOptionsEvent;


class Command
{
    private $requestLine;
    private $arguments;
    private $commandList;
    
    /**
     * 
     * @param string $requestLine
     * @param array $arguments
     */
    public function __construct($requestLine, $arguments)
    {
        try {
            $bootstrap = new Bootstrap();
            $sectionToInit = array('configuration', 'database', 'constants', 'cache', 'logger', 'organization', 'events');
            $bootstrap->init($sectionToInit);
            $this->requestLine = $requestLine;
            $this->arguments = $arguments;
            $modulesToParse = array();
            
            $coreCheck = preg_match("/^core:/", $requestLine);
            if (($coreCheck === 0) || ($coreCheck === false)){
                foreach (glob(__DIR__."/../../modules/*Module") as $moduleTemplateDir) {
                    $modulesToParse[] = basename($moduleTemplateDir);
                }
            }
            $this->parseCommand($modulesToParse);
        } catch (\Exception $e) {
            echo $e;
        }
    }
    
    /**
     * 
     * @param string $username
     * @param string $password
     */
    public function authenticate($username, $password = "")
    {
        echo "Authentication not implemented yet\n";
    }
    
    /**
     * 
     */
    public function getHelp()
    {
        echo "Usage: centreonConsole [-v] [-l] [-h] [-u <user>] [-p <password>] <request> <parameters>\n";
        echo "-v Get Centreon Core version\n";
        echo "-l List available commands\n";
        echo "-h Print this help\n";
        echo "-u / -p To authenticate\n";
        echo "request Command or request to execute, as listed by '-l'\n";
        echo "parameters List of parameters for the request, separated by ':'\n";
    }
    
    /**
     * 
     */
    public function getCommandList()
    {
        $requestLineExploded = explode(':', $this->requestLine);
        
        $nbOfElements = count($requestLineExploded);
        
        if (($nbOfElements == 1) && ($requestLineExploded[0] == "")) {
            $this->displayCommandList($this->commandList);
        } else {
            $module = $requestLineExploded[0];
            $this->displayCommandList($this->commandList[$module], $module);
        }
    }

    /**
     * Display the current installed version
     */
    public function getVersion()
    {
        $dbconn = Di::getDefault()->get('db_centreon');
        try {
            $stmt = $dbconn->query('SELECT value FROM cfg_informations where `key` = "version"');
        } catch (\Exception $e) {
            throw new \Exception("Version not present.");
        }
        if (0 === $stmt->rowCount()) {
            throw new \Exception("Version not present.");
        }
        $row = $stmt->fetch();
        $stmt->closeCursor();
        echo $row['value'] . "\n";
    }
    
    /**
     * 
     * @param array $ListOfCommands
     * @param string $module
     */
    private function displayCommandList($ListOfCommands, $module = null)
    {
        if (!is_null($module)) {
            $ListOfCommands = array($module => $ListOfCommands);
        }

        foreach ($ListOfCommands as $module => $section) {
            if ($module == 'core') {
                $moduleColorized = Colorize::colorizeText($module, "blue", "black", true);
            } else {
                $moduleColorized = Colorize::colorizeText($module, "purple", "black", true);
            }
            echo "[" . $moduleColorized . "]\n";
            foreach ($section as $sectionName => $call) {
                
                $explodedSectionName = explode('\\', $sectionName);
                $nbOfChunk = count($explodedSectionName);
                
                $commandName = "";
                for ($i=0 ;$i<($nbOfChunk-1); $i++) {
                    $commandName .= strtolower($explodedSectionName[$i]) . ':';
                }

                $commandName .= preg_replace('/Command/i', "", $explodedSectionName[$nbOfChunk - 1], 1);
                                
                // Get Action List
                $classReflection = new \ReflectionClass($call);
                $actionList = $classReflection->getMethods(\ReflectionMethod::IS_PUBLIC);
                
                foreach ($actionList as $action) {
                    if (strpos($action->getName(), "Action")) {
                        $actionName = str_replace("Action", "", $action->getName());
                        $colorizedAction = Colorize::colorizeText($actionName, "yellow", "black", true);
                        echo "    $moduleColorized:$commandName:$colorizedAction\n";
                    }
                }
                
                echo "\n";
            }
            echo "\n\n";
        }
    }
    
    public function getFormsParams($aliveObject,$docComment){
        preg_match_all('/@cmdForm\s+(\S+|\/)+\s+(\S+)/', $docComment, $matches);
        $formRoute = null;
        $required = false;
        $attributeMap = false;
        if(!empty($matches[1][0])){
            $formRoute = $matches[1][0];
            if(!empty($matches[2][0])){
                switch($matches[2][0]){
                    case 'required' : 
                        $required = true;
                        break;
                    case 'map' :
                        $attributeMap = true;
                        break;
                    case 'optional' : 
                    default :
                        $required = false;
                        break;
                }
            }
            if(!$attributeMap){
                if (method_exists($aliveObject, 'getFieldsFromForm')) {
                    $aliveObject->getFieldsFromForm($formRoute,$required);
                }
            }else{
                if (method_exists($aliveObject, 'getAttributesMapFromForm')) {
                    $aliveObject->getAttributesMapFromForm($formRoute);
                }
            }
            
        }
    }
    
    public function getObject($aliveObject,$docComment,$globalOptional = false){
        preg_match_all('/@cmdObject\s+(\S+)\s+(\S+)\s*?(.*)?/', $docComment, $matches);
        $objectArray = array();
        if(!empty($matches[1])){
            foreach($matches[1] as $key=>$objectType){
                $objectArray[$key]['objectType'] = $objectType;
            }
        }
        
        if(!empty($matches[2])){
            foreach($matches[2] as $key=>$objectName){
                $objectArray[$key]['objectName'] = $objectName;
            }
        }
        
        if(!empty($matches[3])){
            foreach($matches[3] as $key=>$objectComment){
                $objectArray[$key]['objectComment'] = $objectComment;
            }
        }

        if (method_exists($aliveObject, 'getObject')) {
            $aliveObject->getObject($objectArray,$globalOptional);
        }
        
    }
    
    public function getCustomsParams($aliveObject,$docComment,$globalOptional = false){
        
        preg_match_all('/@cmdParam\s+(\S+)\s+(\S+)\s+(\S+)\s*?(.*)?/', $docComment, $matches);

        $paramsArray = array();
        if(!empty($matches[1])){
            foreach($matches[1] as $key=>$paramType){
                $paramsArray[$key]['paramType'] = $paramType;
            }
        }
        if(!empty($matches[2])){
            foreach($matches[2] as $key=>$paramName){
                $paramsArray[$key]['paramName'] = $paramName;
            }
        }
        if(!empty($matches[3])){
            foreach($matches[3] as $key=>$paramRequired){
                $paramsArray[$key]['paramRequired'] = ($paramRequired == 'required') ? true : false;
            }
        }
        if(!empty($matches[4])){
            foreach($matches[4] as $key=>$paramComment){
                $paramsArray[$key]['paramComment'] = $paramComment;
            }
        }
        
        if (method_exists($aliveObject, 'getCustomsParams')) {
            $aliveObject->getCustomsParams($paramsArray,$globalOptional);
        }
        

    }
    
    
    /**
     * 
     * @throws Exception
     */
    public function executeRequest()
    {
        $requestLineElements = $this->parseRequestLine();
        $module = $requestLineElements['module'];
        $object = ltrim($requestLineElements['object'], '\\');
        $action = $requestLineElements['action'];
        
        if (strtolower($module) != 'core') {
            if (!Informations::isModuleReachable($module)) {
                throw new Exception("The module doesn't exist");
            }
        }
        
        if (!isset($this->commandList[$module][$object])) {
            throw new Exception("The object $object doesn't exist");
        }
        
        $aliveObject = new $this->commandList[$module][$object]();
        
        if (!method_exists($aliveObject, $action)) {
            throw new Exception("The action '$action' doesn't exist");
        }
        
        $classReflection = new \ReflectionClass($aliveObject);
        $methodReflection = $classReflection->getMethod($action);
        $docComment = $methodReflection->getDocComment();
        $methodParams = $methodReflection->getParameters();
        $globalOptional['object'] = false;
        $globalOptional['params'] = false;
        foreach ($methodParams as $param) {
            $globalOptional[$param->getName()] = $param->isOptional();
        }   
        
        $this->getFormsParams($aliveObject, $docComment);
        if (method_exists($aliveObject, 'refreshAttributesMap')) {
            $aliveObject->refreshAttributesMap();
        }
        
        $this->getObject($aliveObject, $docComment, $globalOptional['object']);
        $this->getCustomsParams($aliveObject, $docComment, $globalOptional['params']);
        
        $actionArgs = array();
        $this->getArgs($actionArgs, $aliveObject, $action);

        $pass = array();
        if(isset($actionArgs['object'])){
            $pass[] = $actionArgs['object'];
        }
        if(isset($actionArgs['params'])){
            $pass[] = $actionArgs['params'];
        }
        
        // Call the action
        $aliveObject->named($methodReflection, $pass);
        
        echo "\n";
    }
    
    /**
     * 
     * @return array
     * @throws Exception
     */
    private function parseRequestLine()
    {
        $requestLineExploded = explode(':', $this->requestLine);
        
        $nbOfElements = count($requestLineExploded);
        
        $module = $requestLineExploded[0];
        $object = ucfirst($requestLineExploded[($nbOfElements - 2)]) . 'Command';
        $action = $requestLineExploded[($nbOfElements - 1)] . 'Action';
        
        if ($nbOfElements > 3) {
            //
            $objectRaw = "";
            for ($i=1; $i<($nbOfElements - 2); $i++) {
                $objectRaw .= ucfirst($requestLineExploded[$i]) . '\\';
            }
            $object = $objectRaw . $object;
        
        } elseif ($nbOfElements < 3) {
            throw new Exception("The request is not valid");
        }
        
        return array(
            'module' => $module,
            'object' => $object,
            'action' => $action
        );
    }
    
    /**
     * 
     * @param array $argsList
     * @param type $aliveObject
     * @param string $action
     */
    private function getArgs(array &$argsList, $aliveObject, $action)
    {
        $listOptions = array();
        if(isset($aliveObject->options)){
            $listOptions = $aliveObject->options;
        }
        
        $specs = new OptionCollection();    
        
        foreach ($listOptions as $option => $spec) {
            if ($spec['type'] != 'boolean') {
                if ($spec['multiple']) {
                    $option .= '+';
                } else if ($spec['required']) {
                    $option .= ':';
                } else {
                    $option .= '?';
                }
            }
            $specs->add($option, $spec['help'])->isa($spec['type']);
        }        
        
        $parser = new OptionParser($specs);
        $parsedOptions = self::parseOptions($this->arguments, $parser);
        
        if (isset($aliveObject->objectName)) {
            $events = Di::getDefault()->get('events');
            $manageCommandOptionsEvent = new ManageCommandOptionsEvent($aliveObject->objectName, $action, $listOptions, $parsedOptions);
            $events->emit('core.manage.command.options', array($manageCommandOptionsEvent));
            $listOptions = $manageCommandOptionsEvent->getOptions();
            $aliveObject->options = $listOptions;
        }
        
        $listOptions = array_merge($listOptions,
            array(
            'h|help' => array(
                'help' => 'help',
                'type' => 'boolean',
                'functionParams' => '',
                "toTransform" => '',
                'required' => false,
                'defaultValue' => false)
            )
        );
        
        $specs = new OptionCollection();
        foreach ($listOptions as $option => $spec) {
            if ($spec['type'] != 'boolean') {
                if ($spec['multiple']) {
                    $option .= '+';
                } else if ($spec['required']) {
                    $option .= ':';
                } else {
                    $option .= '?';
                }
            }
            $specs->add($option, $spec['help'])->isa($spec['type']);
        }
        
        try {
            $parser = new OptionParser($specs);
            $optionsParsed = $parser->parse($this->arguments);
        } catch (RequireValueException $ex) {
            echo $ex->getMessage();
        }

        if ($optionsParsed->help) {
            $printer = new ConsoleOptionPrinter();
            echo $printer->render($specs);
            die;
        }
        unset($listOptions['h|help']);
        $this->manageConsoleParams($listOptions,$optionsParsed,$argsList);

    }
    
    private function manageConsoleParams($listOptions,$optionsParsed,&$argsList){
        foreach( $optionsParsed as $key => $spec ) {
            $argsList[$listOptions[$key]['paramType']][$key] = $spec->value;
        }
        
        
        foreach($listOptions as $key=>$options){
            if($options['type'] === 'boolean'){
                if(isset($options['booleanValue'])){
                    if(isset($argsList[$options['paramType']][$key])){
                        $argsList[$options['paramType']][$key] = $options['booleanValue'];
                    }else if(isset($options['booleanSetDefault']) && $options['booleanSetDefault']){
                        $argsList[$options['paramType']][$key] = !$options['booleanValue'];
                    }
                    if (isset($argsList[$options['paramType']][$key]) && $argsList[$options['paramType']][$key]) { //true 
                        $argsList[$options['paramType']][$key] = 1;
                    } else if(isset($argsList[$options['paramType']][$key])){ //false
                        $argsList[$options['paramType']][$key] = 0;
                    }
                }
            }
            if(isset($argsList[$options['paramType']][$key]) && $options['multiple']){
                if(is_array($argsList[$options['paramType']][$key])){
                    $argsList[$options['paramType']][$key] = implode(',',$argsList[$options['paramType']][$key]);
                }
            }
            if(isset($argsList[$options['paramType']][$key])){
                if(!empty($options['attributes']['choices'])){
                    if(isset($options['attributes']['choices'][$argsList[$options['paramType']][$key]])){
                        $argsList[$options['paramType']][$key] = $options['attributes']['choices'][$argsList[$options['paramType']][$key]];
                    }
                }
            }else if(isset($options['defaultValue'])){
                $argsList[$options['paramType']][$key] = $options['defaultValue'];
            }else if($options['required']){
                $missingParams[] = $key;
            }
        }

        if(!empty($missingParams)){
            $errorMessage = 'The following mandatory parameters are missing :';
            foreach($missingParams as $params){
                $errorMessage .= "\n   - ".$params;
            }
            
            throw new \Exception($errorMessage);
        }
    }
    
    /**
     * 
     * @param string $object
     * @param string $method
     */
    public function parseAction($object, $method)
    {
        $classReflection = new \ReflectionClass($object);
        $methodReflection = $classReflection->getMethod($method);
        $docComment = $methodReflection->getDocComment();
        
        preg_match_all('/@param\s+([A-z]+)\s+(\$[A-z]+)(.*)/', $docComment, $matches);
        
        $paramList = array();
        $nbElement = count($matches) - 1;
        for ($i=0; $i<$nbElement; $i++) {
            $pDescription = "";
            $pName = str_replace('$', '', $matches[2][$i]);
            $pType = $matches[1][$i];
            if (isset($matches[3][$i])) {
                $pDescription .= trim($matches[3][$i]);
            }
            
            $paramList[$pName] = array(
                'type' => $pType,
                'description' => $pDescription
            );
        }
        
    }
    
    /**
     * 
     * @param array $modules
     */
    private function parseCommand($modules)
    {
        $this->commandList = array();
        
        // First get the Core one
        $this->getCommandDirectoryContent(realpath(__DIR__."/../commands/"));
        
        // Now lets see the modules
        foreach ($modules as $module) {
            $moduleName = str_replace('Module', '', $module);
            preg_match_all('/[A-Z]?[a-z]+/', $moduleName, $myMatches);
            $moduleShortName = strtolower(implode('-', $myMatches[0]));
            if (Informations::isModuleReachable($moduleShortName)) {
                $this->getCommandDirectoryContent(
                    __DIR__ . "/../../modules/$module/commands/",
                    $moduleShortName,
                    $moduleName
                );
            }
        }
    }
    
    /**
     * 
     * @param string $dirname
     * @param string $module
     * @param string $namespace
     */
    private function getCommandDirectoryContent($dirname, $module = 'core', $namespace = 'Centreon')
    {
        $path = realpath($dirname);
        
        if (file_exists($path)) {
            $listOfDirectories = glob($path . '/*');

            while (count($listOfDirectories) > 0) {
                $currentFolder = array_shift($listOfDirectories);
                if (is_dir($currentFolder)) {
                    $listOfDirectories = array_merge($listOfDirectories, glob($currentFolder . '/*'));
                } elseif (pathinfo($currentFolder, PATHINFO_EXTENSION) == 'php') {
                    $objectName = str_replace(
                        '/',
                        '\\',
                        substr(
                            $currentFolder,
                            (strlen($path) + 1),
                            (strlen($currentFolder) - strlen($path) - 5)
                        )
                    );
                    $this->commandList[$module][$objectName] = '\\'.$namespace.'\\Commands\\'.str_replace('/', '\\', $objectName);
                }
            }
        }
    }

    /**
     *
     * @param array $argv
     * @param OptionParser $parser
     * @return $result
     */
    public function parseOptions(array $argv, $parser)
    {
        $result = array();
        $argv = $parser->preprocessingArguments($argv);
        $len = count($argv);
        for ($i = 0; $i < $len; ++$i)
        {
            $arg = new Argument( $argv[$i] );
            if (! $arg->isOption()) {
                continue;
            }

            $next = null;
            if ($i + 1 < count($argv) )  {
                $next = new Argument($argv[$i + 1]);
            }
            $spec = $parser->specs->get($arg->getOptionName());
            if (! $spec) {
                continue;
            }
            if ($spec->isRequired()) {
                if (! $parser->foundRequireValue($spec, $arg, $next) ) {
                    continue;
                }
                $parser->takeOptionValue($spec, $arg, $next);
                
                if ($next && ! $next->anyOfOptions($parser->specs)) {
                    $result[$spec->getId()] = $next->arg;
                    $i++;
                }
            } 
        }
        return $result;
    }
}
