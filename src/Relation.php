<?php namespace Jimbolino\Laravel\ModelBuilder;

/**
 * Relation, defines one single Relation entry
 *
 * User: Jim
 * Date: 11-4-2015
 * Time: 00:41
 */
class Relation {

    protected $type;
    protected $remoteField;
    protected $localField;
    protected $remoteFunction;
    protected $remoteClass;
    protected $junctionTable;

    public function __construct($type, $remoteField, $remoteTable, $localField, $prefix = '', $junctionTable = '') {
        $this->type = $type;
        $this->remoteField = $remoteField;
        $this->localField = $localField;
        $this->remoteFunction = StringUtils::underscoresToCamelCase(StringUtils::removePrefix($remoteTable,$prefix));
        $this->remoteClass = StringUtils::prettifyTableName($remoteTable,$prefix);
        $this->junctionTable = StringUtils::removePrefix($junctionTable,$prefix);

        if($this->type == 'belongsToMany') {
            $this->remoteFunction = StringUtils::safePlural($this->remoteFunction);
        }
    }

    public function __toString() {
        $string = TAB.'public function '.$this->remoteFunction.'() {'.LF;
        $string .= TAB.TAB.'return $this->'.$this->type.'(';
        $string .= StringUtils::singleQuote($this->remoteClass);

        if($this->type == 'belongsToMany') {
            $string .= ', '.StringUtils::singleQuote($this->junctionTable);
        }

        //if(!NamingConvention::primaryKey($this->localField)) {
            $string .= ', '.StringUtils::singleQuote($this->localField);
        //}

        //if(!NamingConvention::foreignKey($this->remoteField, $this->remoteTable, $this->remoteField)) {
            $string .= ', '.StringUtils::singleQuote($this->remoteField);
        //}

        $string .= ');'.LF;
        $string .= TAB.'}'.LF.LF;
        return $string;
    }
}
