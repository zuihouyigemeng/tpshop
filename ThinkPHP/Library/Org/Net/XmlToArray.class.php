<?php
namespace Org\Net;

class XmlToArray
{
    private $xml;
    private $contentAsName="content" ;
    private $attributesAsName="attributes";
    private $xml_array = array();
 
    public function setXml( $xmlstr )
    {
        $this->xml = $xmlstr ;
        return $this ;
    }
 
    public function setContentAsName( $name )
    {
        $this->contentAsName = $name ;
        return $this ;
    }
 
    public function  setAttributeAsName( $name )
    {
        $this->attributesAsName = $name ;
        return $this ;
    }
 
    private function createXMLArray( $node,&$parent_node,$node_index =0)
    {
        $node_attrbutes= array() ;
        $node_name = $node->getName() ;
        $attributes = $node->attributes() ;
        $children  = $node->children () ;
 
 
    // 遍历节点上的所有属性
        foreach( $attributes as $attrname => $attrvalue )
        {
            $attrvalue  = ( string )$attrvalue ;
            $node_attrbutes[ $attrname ] = trim( $attrvalue ) ;
        }
        $content = "";
        if( count($children) == 0 )
        {
            $content =   ( string ) $node   ;
        }
 
        $node_array = array(
           $this->attributesAsName =>$node_attrbutes ,
           $this->contentAsName => trim( $content )
        );
//  设置层级关系
            if(  !isset( $parent_node[ $node_name ] )  )
            {
                $is = count( $parent_node ) ;
                if(  !isset( $parent_node[ $this->attributesAsName ] ) && count( $parent_node ) > 0 )
                {
 
                    $last_index = count( $parent_node ) -1 ;
                   $parent_node =& $parent_node[ $last_index ];
                   $parent_node[ $node_name ] = $node_array ;
                }
                else
                {
                    $parent_node[ $node_name ] = $node_array ;
                }
            }
            else
            {
                     $append  = &$parent_node[ $node_name ] ;
                   if( isset( $append[ $this->attributesAsName ] ) )
                   {
                       $parent_node[ $node_name ]  = array( $append );
                       $append  = &$parent_node[ $node_name ] ;
 
                   }
                   if( isset( $append[ $node_index ] ) )
                   {
                       $append =  &$append[ $node_index ] ;
                   }
                // 追加
                    array_push( $append ,  $node_array ) ;
            }
 
        $index = 0 ;
        // 递归操作
        foreach( $children as $childnode )
        {
            $parent =  &$parent_node[ $node_name  ] ;
            $this->createXMLArray( $childnode ,$parent,$index ++ );
        }
       return  $parent_node ;
    }
 
    public  function  parseXml( $isjson=false)
    {
        $root = simplexml_load_string ( $this->xml ) ;
        $parent_node = array();
        $array = $this->createXMLArray( $root ,$parent_node ) ;
 
        return $isjson ?  json_encode( $array ) : $array ;
    }
}