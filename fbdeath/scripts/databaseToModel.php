<?php

ini_set('auto_detect_line_endings', TRUE);

require_once('Zend/Db/Table/Abstract.php');
require_once('Zend/CodeGenerator/Php/Class.php');
require_once('Zend/CodeGenerator/Php/Docblock.php');
require_once('Zend/CodeGenerator/Php/Parameter.php');
require_once('Zend/CodeGenerator/Php/Parameter/DefaultValue.php');
require_once('Zend/CodeGenerator/Php/Property.php');
//require_once('PHPUnit/Framework/TestSuite.php');

// VARIABLES
$path		= array(	'base'		=> 	'../application/',
						'mapper'	=>	'Mapper/',
						'model'		=>	'Model/',
						'dbTable'	=>	'DbTable/'	);
$package	= array(	'base'		=> 	'Persist_MySpace',
						'mapper'	=>	'Mapper',
						'model'		=>	'Model',
						'dbTable'	=>	'DbTable'	);


// DB CONNECTION
$db = 	Zend_Db::factory(
			'PDO_MYSQL', 
			array(	'host'     => '127.0.0.1',
			    	'username' => 'root',
			    	'password' => '',
			    	'dbname'   => 'fbdeath')
		);

Zend_Db_Table_Abstract::setDefaultAdapter($db);


// get all tables in db
$tables = $db->listTables();
foreach($tables as $table) {
	
	// need to remove underline first, ucwords, and then remove space
	$name = str_replace(' ', '', ucwords(str_replace('_', ' ', $table)));
	
	$generator = new ModelGenerator($table, 
									$name, 
									$package['base'],  
									$package['model'],
									$package['dbTable'],
									$package['mapper']	);
	
	/**
	 * CREATE MODEL FILE
	 */
	$code = $generator->model();
	
	// Output code to file
	if (!is_dir($path['base'] . $path['model']))
		mkdir($path['base'] . $path['model'], 0777, true);
	$file = $path['base'] . $path['model']. $name . '.php';
	echo $file."\n";
	file_put_contents($file, '<?php' . PHP_EOL . $code);
	
	
	/**
	 * CREATE DBTABLE FILE
	 */	
	$dbCode = $generator->dbTable();
	
	//echo $db_class->generate() . PHP_EOL;
	if(!is_dir($path['base'] . $path['dbTable']))
		mkdir($path['base'] . $path['dbTable'], 0777, true);
	$file = $path['base'] . $path['dbTable']. $name . '.php';
	echo $file."\n";
	file_put_contents($file, '<?php' . PHP_EOL . $dbCode);
	
	/**
	 * CREATE MAPPER FILE
	 */	
	$code = $generator->mapper();
	
	//echo $db_class->generate() . PHP_EOL;
	if(!is_dir($path['base'] . $path['mapper']))
		mkdir($path['base'] . $path['mapper'], 0777, true);
	$file = $path['base'] . $path['mapper']. $name . '.php';
	echo $file."\n";
	file_put_contents($file, '<?php' . PHP_EOL . $code);
	
}





class ModelGenerator {
	
	protected $tableName;
	protected $className;
	
	protected $package;
	protected $model;
	protected $mapper;
	protected $dbTable;
	
	protected $author = 'Jed R. Brubaker';
	
	protected $primary;
	protected $columns;
	
	function __construct($tableName, $className, $package = null, $model = null, $dbTable = null, $mapper = null) {

		
		if (!$tableName)
			throw new Exception("ModelGenerator: Table name not provided. Aborting.");		
		if (!$className)
			throw new Exception("ModelGenerator: Class name not provided. Aborting.");
			
		$this->tableName 	= $tableName;	
		$this->className 	= $className;
		
		$this->package 		= $package;
		$this->model 		= $model;
		$this->mapper 		= $mapper;
		$this->dbTable 		= $dbTable;		
		
		// get all fields		
		$this->parseFields();
		
		
	}
	
	
	
	/**
	 * 
	 */
	private function parseFields() {

		// Get all fields
		$fields = $this->getDb()->describeTable($this->tableName);		
		// want to track primary ids for table
		$primary = array();		
		// add to columns each field with a default value
		$columns = array();
		foreach($fields as $field) {
				
			// if int field default to 0
			$columns[$field['COLUMN_NAME']] = strpos($field['DATA_TYPE'], 'int') !== false ? 0 : '';
	
			// track primary field(s) for table
			if($field['PRIMARY']) {
				$primary[] = $field['COLUMN_NAME'];
			}
		}
		
		$this->primary = $primary;
		$this->columns = $columns;
	}

	
	/**
	 * Build the DocBlock for the top of the class file.
	 */
	private function buildDocBlock($description) {
		$docblock = new Zend_CodeGenerator_Php_Docblock(
			array('shortDescription' => $description,
				'tags' => array(
					array(	'name' 			=> 'author',
							'description' 	=> $this->author
					)
				)
			)
		);
		return $docblock;
	}
	
	private function convertToBumpyCaps($field) {
		// fix column name
		$propertyPieces = explode("_", $field);
		$property = "";
		
		foreach ($propertyPieces as $piece)
			$property .= ucfirst($piece);	
		
		$property[0] = strtolower($property[0]);
		
		return $property;
		
	}

	private function getDb() {
		$db = Zend_Db_Table_Abstract::getDefaultAdapter();
		if (!$db)
			throw new Exception("ModelGenerator: Unable to get database connection. Aborting.");
		
		return $db;
	}
	
	public function model() {
		/**
		 * BUILD MODEL
		 */
		
		if (!$this->model)
			throw new Exception("ModelGenerator: model not set. Aborting.");
	
		// create new class generator
		$class = new Zend_CodeGenerator_Php_Class();
	
		// build docblock
		$docBlock = $this->buildDocBlock($this->className . ' model');
		
		// set name and docblock
		$class->setName($this->package. "_" . $this->model . "_" . $this->className);
		$class->setExtendedClass("Persist_Model");
		$class->setDocblock($docBlock);
		
		// Build constructor
		$class->setMethod(		array(	'name'			=> "__construct",
										'parameters'	=> array(array('name' => 'options', 'type' => 'array', 'defaultValue' => new Zend_CodeGenerator_Php_Parameter_DefaultValue('null'))),
										'body'			=> 	'parent::__construct();'."\n".
															'if (is_array($options)) {'."\n".
															'	$this->setOptions($options);'."\n".
        													'}'));
		
		// add data array property to class
		foreach ($this->columns as $key => $defaultValue) {
			
			$class->setProperty(	array(	'name'			=> $this->convertToBumpyCaps($key),
											'visibility' 	=> 'protected'/*,
											'defaultValue'	=> $defaultValue*/	));
			
			$class->setMethod(		array(	'name'			=> "set".ucfirst($this->convertToBumpyCaps($key)),
											'parameters'	=> array(array('name' => 'value')),
											'body'			=> '$this->'.$this->convertToBumpyCaps($key).' = $value;'."\n".'return $this;'));
			
			$class->setMethod(		array(	'name'			=> "get".ucfirst($this->convertToBumpyCaps($key)),
											'body'			=> 'return $this->'.$this->convertToBumpyCaps($key).';'));			
		}
		
		// Build options method
		$class->setMethod(		array(	'name'			=> "setOptions",
										'parameters'	=> array(array('name' => 'options', 'type' => 'array')),
										'body'			=> 	'$methods = get_class_methods($this);'."\n".
													        'foreach ($options as $key => $value) {'."\n".
													        '	$method = \'set\' . ucfirst($key);'."\n".
													        '	if (in_array($method, $methods)) {'."\n".
													        '		$this->$method($value);'."\n".
													        '	}'."\n".
													        '}'."\n".
													        'return $this;'));

		
		// Build toArray method
		$toArrayBody = '$data = array();'."\n";
		foreach ($this->columns as $key => $defaultValue) {
			$toArrayBody .=	'$data[\''.$this->convertToBumpyCaps($key).'\'] = $this->'.$this->convertToBumpyCaps($key).';'."\n";
		}		
		$toArrayBody .= 'return $data;';
		
		$class->setMethod(		array(	'name'			=> "toArray",
										'body'			=> 	$toArrayBody));
									
		return $class->generate();
		
	}
	
	function dbTable() {
		/** 
		 * CREATE DBTABLE
		 */
		
		if(!$this->dbTable)
			throw new Exception("ModelGenerator: dbTable not set. Aborting.");
		
		// create zend_db_table_abstract
		$db_class = new Zend_CodeGenerator_Php_Class();
		$db_class->setName($this->package ."_". $this->dbTable ."_". $this->className);
		$db_class->setExtendedClass('Zend_Db_Table_Abstract');
		
		// build docblock
		$docBlock = $this->buildDocBlock($this->className . ' DbTable');
		// set docblock
		$db_class->setDocblock($docBlock);
		
		$db_class->setProperty(
			array(	'name' 			=> '_name',
					'visibility' 	=> 'protected',
					'defaultValue' 	=> $this->tableName,
					'docblock' 		=> 
						array(	'tags' 	=> 
							array(	
								new Zend_CodeGenerator_Php_Docblock_Tag(
									array(	'name' 			=> 'var',
											'description' 	=> 'string name of db table' )
								)
							)
						)
			)
		);
		
		if(count($this->primary)) {
			$db_class->setProperty(
				array(	'name' 			=> '_primary',
						'visibility' 	=> 'protected',
						'defaultValue' 	=> count($this->primary) > 1 ? $this->primary : $this->primary[0],
						'docblock' 		=> array(
							'tags' => array(
								new Zend_CodeGenerator_Php_Docblock_Tag(
									array(
										'name' 			=> 'var',
										'description' 	=> 'string or array of fields in table'
									)
								)
							)
						)
				)
			);
		}	
		return $db_class->generate();
	}
	
	function mapper() {
	
		if(!$this->mapper)
			throw new Exception("ModelGenerator: mapper not set. Aborting.");
		
		// create zend_db_table_abstract
		$class = new Zend_CodeGenerator_Php_Class();
		$class->setName($this->package ."_". $this->mapper ."_". $this->className);
		$class->setExtendedClass('Persist_Mapper');
		
		// build docblock
		$docBlock = $this->buildDocBlock($this->className . ' Mapper');
		// set docblock
		$class->setDocblock($docBlock);
				
		// Build constructor
		$class->setMethod(		array(	'name'			=> "__construct",
										'body'			=> 	'$this->dbTableName = "'.$this->package. "_" . $this->dbTable . "_" . $this->className.'";'));
		
		// Build save
		$saveBody = '$data = array();'."\n";
		// Map data
		foreach ($this->columns as $key => $defaultValue) {
			if ($this->convertToBumpyCaps($key) != "id")
				$saveBody .=	'$data[\''.$key.'\'] = $model->'.$this->convertToBumpyCaps($key).';'."\n";
		}		
		$saveBody	.= "\n\n";		
		
		$saveBody	.=	'if (null === ($id = $model->getId())) {'."\n".
						'	unset($data[\'id\']);'."\n".
						'	$response = $this->getDbTable()->insert($data);'."\n".
						'} else {'."\n".
						'	$this->getDbTable()->update($data, array(\'id = ?\' => $id));'."\n".           	
						'	$response = $model->getId();'."\n".
						'}'."\n\n";
		
		$saveBody .= 'return $response;';
		
		$class->setMethod(		array(	'name'			=> 'save',
										'parameters'	=> array(array('name' => 'model', 'type' => $this->package. "_" . $this->model . "_" . $this->className)),
										'body'			=> $saveBody ));
		
		// Build populate
		$populateBody = "";
		foreach ($this->columns as $key => $defaultValue) {
			$populateBody .=	'$model->set'.ucfirst($this->convertToBumpyCaps($key)).'($row->'.$key.');'."\n";
		}	
		$populateBody .= "\n".'return $model;';
		
		$class->setMethod(		array(	'name'			=> 'populateModel',
										'parameters'	=> array(array('name' => 'row'), array('name' => 'model', 'passedByReference' => true)),
										'body'			=> $populateBody ));
		
		return $class->generate();
	
		
	//    SAMPLE CODE TO REPLICATE ---

	//    protected function populateModel($row, &$model) {
	//    	$model	->setId($row->id);
	//        $model	->setLine1($row->line1);
	//        $model	->setLine2($row->line2);
	//        $model	->setLine3($row->line3);
	//        $model	->setCity($row->city);
	//        $model	->setState($row->state);
	//        $model	->setZipcode($row->zipcode);
	//        
	//        $model	->setCreated($row->created);
	//               
	//        return $model;    	
	//    }	
	
	}
}

?>