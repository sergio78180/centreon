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

namespace CentreonConfiguration\Models\Relation\Service;

use Centreon\Models\CentreonRelationModel;

class Hosttemplate extends CentreonRelationModel
{
    protected static $relationTable = "cfg_services_hosts_templates_relations";
    protected static $firstKey = "service_id";
    protected static $secondKey = "host_tpl_id";
    public static $firstObject = "\CentreonConfiguration\Models\Service";
    public static $secondObject = "\CentreonConfiguration\Models\Host";
    
    /**
     * 
     * @param type $firstTableParams
     * @param type $secondTableParams
     * @param type $count
     * @param type $offset
     * @param type $order
     * @param type $sort
     * @param type $filters
     * @param type $filterType
     * @param type $relationTableParams
     * @return type
     */
    public static function getMergedParametersBySearch(
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
        $filters['service_register'] = '1';
        $aAddFilters = array();
        $tablesString =  '';
        
        if (array('tagname', array_values($filters)) && !empty($filters['tagname'])) {
            $aAddFilters = array(
                'tables' => array('cfg_tags', 'cfg_tags_services'),
                'join'   => array('cfg_tags.tag_id = cfg_tags_services.tag_id', 'cfg_tags.tag_id = cfg_tags_services.tag_id',
                    'cfg_tags_services.resource_id = cfg_services.service_id ')
            ); 
        }
        
        return parent::getMergedParametersBySearch(
            $firstTableParams,
            $secondTableParams,
            $count,
            $offset,
            $order,
            $sort,
            $filters,
            $filterType,
            $relationTableParams,
            $aAddFilters
        );
    }
}
