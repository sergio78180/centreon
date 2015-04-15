<?php
/*
 * Copyright 2005-2015 CENTREON
 * Centreon is developped by : Julien Mathis and Romain Le Merlus under
 * GPL Licence 2.0.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation ; either version 2 of the License.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
 * PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, see <http://www.gnu.org/licenses>.
 *
 * Linking this program statically or dynamically with other modules is making a
 * combined work based on this program. Thus, the terms and conditions of the GNU
 * General Public License cover the whole combination.
 *
 * As a special exception, the copyright holders of this program give CENTREON
 * permission to link this program with independent modules to produce an executable,
 * regardless of the license terms of these independent modules, and to copy and
 * distribute the resulting executable under terms of CENTREON choice, provided that
 * CENTREON also meet, for each linked independent module, the terms  and conditions
 * of the license of that module. An independent module is a module which is not
 * derived from this program. If you modify this program, you may extend this
 * exception to your version of the program, but you are not obliged to do so. If you
 * do not wish to do so, delete this exception statement from your version.
 *
 * For more information : contact@centreon.com
 *
 *
 */

namespace CentreonConfiguration\Models\Relation\Host;

use Centreon\Internal\Di;
use Centreon\Models\CentreonRelationModel;

class Service extends CentreonRelationModel
{
    protected static $relationTable = "cfg_hosts_services_relations";
    protected static $firstKey = "host_host_id";
    protected static $secondKey = "service_service_id";
    public static $firstObject = "\CentreonConfiguration\Models\Host";
    public static $secondObject = "\CentreonConfiguration\Models\Service";

    public static function getMergedParameters(
        $firstTableParams = array(),
        $secondTableParams = array(),
        $count = -1,
        $offset = 0,
        $order = null,
        $sort = "ASC",
        $filters = array(),
        $filterType = "OR",
        $relationTableParams = array()
    ) {
        $fString = "";
        $sString = "";
        $rString = "";
        $firstObj = static::$firstObject;
        foreach ($firstTableParams as $fparams) {
            if ($fString != "") {
                $fString .= ",";
            }
            $fString .= $firstObj::getTableName().".".$fparams;
        }
        $secondObj = static::$secondObject;
        foreach ($secondTableParams as $sparams) {
            if ($fString != "" || $sString != "") {
                $sString .= ",";
            }
            $sString .= $secondObj::getTableName().".".$sparams;
        }
        foreach ($relationTableParams as $rparams) {
            if ($fString != "" || $sString != "" || $rString != "") {
                $rString .= ",";
            }
            $rString .= static::$relationTable.".".$rparams;
        }
        $sql = "SELECT $fString $sString $rString
            FROM ". $firstObj::getTableName().",".$secondObj::getTableName().","
            . static::$relationTable."
            WHERE ".$firstObj::getTableName().".".$firstObj::getPrimaryKey()." = "
            . static::$relationTable.".".static::$firstKey."
            AND ".static::$relationTable.".".static::$secondKey ." = "
            . $secondObj::getTableName().".".$secondObj::getPrimaryKey() . "
            AND host_register = '1'
            AND service_register = '1' ";
        
        $filterTab = array();
        if (count($filters)) {
            $sql .= " AND ( ";
            $first = true;
            foreach ($filters as $key => $rawvalue) {
                if ($first) {
                    $first = false;
                } else {
                    $sql .= $filterType;
                }
                $sql .= " $key LIKE ? ";
                $value = trim($rawvalue);
                $value = str_replace("\\", "\\\\", $value);
                $value = str_replace("_", "\_", $value);
                $value = str_replace(" ", "\ ", $value);
                $filterTab[] = $value;
            }
            $sql .= " ) ";
        }
        if (isset($order) && isset($sort) && (strtoupper($sort) == "ASC" || strtoupper($sort) == "DESC")) {
            $sql .= " ORDER BY $order $sort ";
        }
        if (isset($count) && $count != -1) {
            $db = Di::getDefault()->get('db_centreon');
            $sql = $db->limit($sql, $count, $offset);
        }
        
        $result = static::getResult($sql, $filterTab);
        return $result;
    }
    
    /**
     * 
     * @param int $iIdHost
     * @param int $iIdService
     * @return int
     */
    public static function isExistServiceIsHost($iIdHost, $iIdService)
    {
        $iNb = 0;
        $sql = "SELECT count(*) as nb FROM ".static::$relationTable." "
                . " WHERE ".static::$secondKey." = ".$iIdService." "
                . " AND ".static::$firstKey." = ".$iIdHost;
        
        //echo $sql;
        $result = static::getResult($sql);
        if (isset($result[0]['nb'])) {
            $iNb = $result[0]['nb'];
        }
        return $iNb;
    }
    /**
     * 
     * @param int $iIdHost
     * @param int $iIdHostTemplate
     * @return  array List of service ID
     */
    public static function getServiceByHostAndHostTemplate($iIdHost, $iIdHostTemplate)
    {
        if (empty($iIdHost) or empty($iIdHostTemplate)) {
            return array();
        }

        $sql = "SELECT ".static::$secondKey."
            FROM ". static::$relationTable." WHERE ".static::$relationTable.".".static::$firstKey ." = ". $iIdHost
            ." AND host_template_id = ".$iIdHostTemplate;
           
        $result = static::getResult($sql);
        return $result;
    }
    /**
     * Method to insert relation
     * host and hosttemplate and service
     * @param type $firstKey
     * @param type $secondKey
     * @param type $thirdKey
     */
    public static function insert($firstKey, $secondKey, $thirdKey)
    {
        $db = Di::getDefault()->get('db_centreon');
        
        $sql = "INSERT INTO ".static::$relationTable
            ." (".static::$firstKey.", ".static::$secondKey.", host_template_id) "
            . "VALUES (?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->execute(array($firstKey, $secondKey, $thirdKey));
    }
    /**
     * Method to display service
     * @param int $firstKey
     */  
    public static function display($firstKey)
    {
        if (empty($firstKey))
        {
            return;
        }
        $db = Di::getDefault()->get('db_centreon');
                
        $sql = "UPDATE cfg_services SET service_activate = 0 WHERE service_id = ? ";
        $stmt = $db->prepare($sql);
        $stmt->execute(array($firstKey));
    }
}
