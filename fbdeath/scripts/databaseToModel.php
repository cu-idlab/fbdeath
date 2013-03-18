<?php

ini_set('auto_detect_line_endings', TRUE);
ini_set('include_path', ini_get('include_path')."/Users/whatknows/Zend/workspaces/DefaultWorkspace/fbdeath/vendor/zendframework/zendframework/library");


require_once 'Zend/Loader/ClassMapAutoloader.php';
require_once 'Zend/Loader/StandardAutoloader.php';
$autoLoader = new Zend\Loader\StandardAutoloader(array(
		'prefixes' => array(
				'MyVendor' => __DIR__ . '/MyVendor',
		),
		'namespaces' => array(
				'MyNamespace' => __DIR__ . '/MyNamespace',
		),
		'fallback_autoloader' => true,
));
$autoLoader->register();

// register our StandardAutoloader with the SPL autoloader

$autoLoader->register();
require_once 'Zend/Db/Adapter/AdapterInterface.php';
require_once 'Zend/Db/Adapter/Profiler/ProfilerAwareInterface.php';
require_once 'Zend/Db/Adapter/Adapter.php';


// require_once('Zend/Db/Table/Abstract.php');
// require_once('Zend/CodeGenerator/Php/Class.php');
// require_once('Zend/CodeGenerator/Php/Docblock.php');
// require_once('Zend/CodeGenerator/Php/Parameter.php');
// require_once('Zend/CodeGenerator/Php/Parameter/DefaultValue.php');
// require_once('Zend/CodeGenerator/Php/Property.php');
//require_once('PHPUnit/Framework/TestSuite.php');

// VARIABLES
$path		= array(	'base'		=> 	'../module/Database/src/Database/Model/',
						'mapper'	=>	'Mapper/',
						'model'		=>	'Model/',
						'dbTable'	=>	'Model/'	);
$package	= array(	'base'		=> 	'Persist_MySpace',
						'mapper'	=>	'Mapper',
						'model'		=>	'Model',
						'dbTable'	=>	'Model'	);

// DB CONNECTION
$dbParams = array(
		'database'  => 'fbdeath',
		'username'  => 'root',
		'password'  => '',
		'hostname'  => '127.0.0.1',
);
$db = new Zend\Db\Adapter\Adapter(array(
	               'driver'    => 'pdo',
                    'dsn'       => 'mysql:dbname='.$dbParams['database'].';host='.$dbParams['hostname'],
                    'database'  => $dbParams['database'],
                    'username'  => $dbParams['username'],
                    'password'  => $dbParams['password'],
                    'hostname'  => $dbParams['hostname'],
));


// Get all the tables in the database
$tableArray = array();
$statement = $db->query("SHOW TABLES");
$results = $statement->execute();
foreach($results as $result) {
    $tableArray[] = array_pop($result);
}

foreach($tableArray as $table) {
	
	// need to remove underline first, ucwords, and then remove space
	$name = str_replace(' ', '', ucwords(str_replace('_', ' ', $table)));
	
	$generator = new ModelGenerator($db, 
	        						$table, 
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
	if (!is_dir($path['base']))
		mkdir($path['base'], 0777, true);
	$file = $path['base'] .  $name . '.php';
	echo $file."\n";
	file_put_contents($file, '<?php' . PHP_EOL . $code);
	
	/**
	 * CREATE DBTABLE FILE
	 */	
	$dbCode = $generator->dbTable();

	//echo $db_class->generate() . PHP_EOL;
	if(!is_dir($path['base']))
		mkdir($path['base'], 0777, true);
	$file = $path['base'] . $name . 'Table.php';
	echo $file."\n";
	file_put_contents($file, '<?php' . PHP_EOL . $dbCode);
	
	/**
	 * CREATE MAPPER FILE
	 */	
// 	$code = $generator->mapper();
	
// 	//echo $db_class->generate() . PHP_EOL;
// 	if(!is_dir($path['base'] . $path['mapper']))
// 		mkdir($path['base'] . $path['mapper'], 0777, true);
// 	$file = $path['base'] . $path['mapper']. $name . '.php';
// 	echo $file."\n";
// 	file_put_contents($file, '<?php' . PHP_EOL . $code);
	
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
	
	protected $db;
	
	function __construct(Zend\Db\Adapter\Adapter $db, $tableName, $className, $package = null, $model = null, $dbTable = null, $mapper = null) {

		
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

		$this->db			= $db;
		
		// get all fields		
		$this->parseFields();
		
		
	}
	
	
	
	/**
	 * 
	 */
	private function parseFields() {

	    $meta = new Zend\Db\Metadata\Metadata($this->db);
	    $table = $meta->getTable($this->tableName);
	    
		// Get all fields
		$fields = $table->getColumns();
		// want to track primary ids for table
		$primary = array();		
		// add to columns each field with a default value
		$columns = array();
		foreach($fields as $field) {
				
			// if int field default to 0
			$columns[$field->getName()] = strpos($field->getDataType(), 'int') !== false ? 0 : '';
	
			// track primary field(s) for table
// 			if($field->getConstraint()->isPrimaryKey()) {
// 				$primary[] = $field['COLUMN_NAME'];
// 			}
		}
		
// 		$this->primary = $primary;
		$this->columns = $columns;
	}

	
	/**
	 * Build the DocBlock for the top of the class file.
	 */
	private function buildDocBlock($description) {
		$docblock = new Zend\Code\Generator\DocBlockGenerator(
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
		if (!$this->db)
			throw new Exception("ModelGenerator: Unable to get database connection. Aborting.");
		
		return $this->db;
	}
	
	public function model() {
		/**
		 * BUILD MODEL
		 */
		
		if (!$this->model)
			throw new Exception("ModelGenerator: model not set. Aborting.");
	
		// create new class generator
		$class = new Zend\Code\Generator\ClassGenerator();
	
		// build docblock
		$docBlock = $this->buildDocBlock($this->className . ' model');
		
		// set name and docblock
		$class->setNamespaceName("Database\Model");

		$class->addUse("Zend\InputFilter\Factory", "InputFactory");
		$class->addUse("Zend\InputFilter\InputFilter");
		$class->addUse("Zend\InputFilter\InputFilterAwareInterface");
		$class->addUse("Zend\InputFilter\InputFilterInterface");

		
		$class->setName($this->className);
// 		$class->setExtendedClass("Persist_Model");
		$class->setImplementedInterfaces(array("InputFilterAwareInterface"));        
		$class->setDocblock($docBlock);
		
		// Build constructor
// 		$class->addMethod(	"__construct", 
// 		        			array(	'name'			=> "__construct",
// 									'parameters'	=> array(	'name' => 'options', 
// 										        					'type' => 'array', 
// 										        					'defaultValue' => null),
// 									'body'			=> 	'parent::__construct();'."\n".
// 														'if (is_array($options)) {'."\n".
// 														'	$this->setOptions($options);'."\n".
//         												'}'));
		
		// add data array property to class
		$exchangeArrayBody .= "";
		foreach ($this->columns as $key => $defaultValue) {
			
			$class->addProperty(	$this->convertToBumpyCaps($key) /* name */, 
			        				$defaultValue, 
			        				Zend\Code\Generator\PropertyGenerator::FLAG_PROTECTED);

			$class->addMethod(		"set".ucfirst($this->convertToBumpyCaps($key)),
									array(	'parameters'	=> 'value'),
			        				null,
			        				'$this->'.$this->convertToBumpyCaps($key).' = $value;'."\n".'return $this;');
			
			$class->addMethod(		"get".ucfirst($this->convertToBumpyCaps($key)),
									array(),
			        				null,
			        				'return $this->'.$this->convertToBumpyCaps($key).';');	

			$exchangeArrayBody .=	'$this->'.$this->convertToBumpyCaps($key).' = (isset($data[\''.$this->convertToBumpyCaps($key).'\'])) ? $data[\''.$this->convertToBumpyCaps($key).'\'] : null;'."\n";
		}
		
		// Build utility functions
		
		// exchangeArray
		$class->addMethod(	"exhcangeArray",
		        			array(	'parameters'	=> 'data'),
		        			null,
		        			$exchangeArrayBody);
// 		public function exchangeArray ($data)
// 		{
// 			$this->id = (isset($data['id'])) ? $data['id'] : null;
// 			$this->artist = (isset($data['artist'])) ? $data['artist'] : null;
// 			$this->title = (isset($data['title'])) ? $data['title'] : null;
// 		}
		
		// getArrayCopy
		$class->addMethod(	"getArrayCopy",
		        			array(),
		        			null,
		        			'return get_object_vars($this);');
		        			

		// setInputFiler
		$class->addMethod(	"setInputFilter",
		        			array(	new Zend\Code\Generator\ParameterGenerator(	"inputFilter", 
		        			        											"InputFilterInterace")), 
		        			null, 
		        			'throw new \Exception("Not used");');
		
		
		
		// Build options method
// 		$class->setMethod(		array(	'name'			=> "setOptions",
// 										'parameters'	=> array(array('name' => 'options', 'type' => 'array')),
// 										'body'			=> 	'$methods = get_class_methods($this);'."\n".
// 													        'foreach ($options as $key => $value) {'."\n".
// 													        '	$method = \'set\' . ucfirst($key);'."\n".
// 													        '	if (in_array($method, $methods)) {'."\n".
// 													        '		$this->$method($value);'."\n".
// 													        '	}'."\n".
// 													        '}'."\n".
// 													        'return $this;'));

		
		// Build toArray method
		$toArrayBody = '$data = array();'."\n";
		foreach ($this->columns as $key => $defaultValue) {
			$toArrayBody .=	'$data[\''.$this->convertToBumpyCaps($key).'\'] = $this->'.$this->convertToBumpyCaps($key).';'."\n";
		}		
		$toArrayBody .= 'return $data;';
		
		$class->addMethod(		"toArray",
		        				array(),
		        				null,
		        				$toArrayBody);
									
		return $class->generate();
		
	}
	
	function dbTable() {
		/** 
		 * CREATE DBTABLE
		 */
		    
		if(!$this->dbTable)
			throw new Exception("ModelGenerator: dbTable not set. Aborting.");
	
		// create class
		$db_class = new Zend\Code\Generator\ClassGenerator();
		$db_class->setNamespaceName("Database\Model");
		$db_class->addUse("Zend\Db\TableGateway\TableGateway");
		$db_class->setName($this->className."Table");
		
		// build docblock
		$docBlock = $this->buildDocBlock($this->className . ' DbTable');
		// set docblock
		$db_class->setDocblock($docBlock);
		
		
		// add parameters
		$db_class->addProperty("tableGateway", null, Zend\Code\Generator\PropertyGenerator::FLAG_PROTECTED);
		
		// add methods
		$db_class->addMethod(	"__construct", 
		        				array(	new Zend\Code\Generator\ParameterGenerator(	"tableGateway", 
		        			        												"TableGateway")),
		        				null, 
		        				'$this->tableGateway = $tableGateway;');
		
		$db_class->addMethod(	"fetchAll",
		        				array(),
		        				null,
		        				'$resultSet = $this->tableGateway->select();'."\n".
								'return $resultSet;');
		
		$db_class->addMethod(	"get".$this->tableName,
		        				array("id"),
		        				null,
		       					'$id  = (int) $id;'."\n".
		        				'$rowset = $this->tableGateway->select(array(\'id\' => $id));'."\n".
		        				'$row = $rowset->current();'."\n".
		        				'if (!$row) {'."\n".
		        				"\t".'throw new \Exception("Could not find row $id");'."\n".
		       					'}'."\n".
		       					'return $row;'	        
		        				);
		
		$saveBody .= "";
		foreach ($this->columns as $key => $defaultValue) {
		    $saveBody .= "\t'".$key."'      \t=> $".$this->tableName."->$key,\n";
		}
		$db_class->addMethod(	"save".ucfirst($this->tableName),
		       					array(	new Zend\Code\Generator\ParameterGenerator(	$this->tableName, 
		        			        												ucfirst($this->tableName))),
		        				null,
		        				
		        				'$data = array('."\n".
		        				$saveBody.
		        				');'."\n".
		        				
		        				'$id = (int) $'.$this->tableName.'->id;'."\n".
		        				'if ($id == 0) {'."\n".
		        				'	$this->tableGateway->insert($data);'."\n".
		        				'} else {'."\n".
		        				'	if ($this->get'.$this->tableName.'($id)) {'."\n".
		        				'		$this->tableGateway->update($data, array(\'id\' => $id));'."\n".
		        				'	} else {'."\n".
		        				'		throw new \Exception(\'id does not exist\');'."\n".
		        				'	}'."\n".
		        				'}');
		
		$db_class->addMethod(	"delete".ucfirst($this->tableName),
		        				array("id"),
		        				null,
		        				'$this->tableGateway->delete(array(\'id\' => $id));');
		
// 		class AlbumTable
// 		{
// 			protected $tableGateway;
		
// 			public function __construct(TableGateway $tableGateway)
// 			{
// 				$this->tableGateway = $tableGateway;
// 			}
		
// 			public function fetchAll()
// 			{
// 				$resultSet = $this->tableGateway->select();
// 				return $resultSet;
// 			}
		
// 			public function getAlbum($id)
// 			{
// 				$id  = (int) $id;
// 				$rowset = $this->tableGateway->select(array('id' => $id));
// 				$row = $rowset->current();
// 				if (!$row) {
// 					throw new \Exception("Could not find row $id");
// 				}
// 				return $row;
// 			}
		

		
// 			public function deleteAlbum($id)
// 			{
// 				$this->tableGateway->delete(array('id' => $id));
// 			}
// 		}
		
// 		$db_class->setProperty(
// 			array(	'name' 			=> '_name',
// 					'visibility' 	=> 'protected',
// 					'defaultValue' 	=> $this->tableName,
// 					'docblock' 		=> 
// 						array(	'tags' 	=> 
// 							array(	
// 								new Zend\Code\Generator\DocBlock\Tag(
// 									array(	'name' 			=> 'var',
// 											'description' 	=> 'string name of db table' )
// 								)
// 							)
// 						)
// 			)
// 		);
		
// 		if(count($this->primary)) {
// 			$db_class->setProperty(
// 				array(	'name' 			=> '_primary',
// 						'visibility' 	=> 'protected',
// 						'defaultValue' 	=> count($this->primary) > 1 ? $this->primary : $this->primary[0],
// 						'docblock' 		=> array(
// 							'tags' => array(
// 								new Zend\Code\Generator\DocBlock\Tag(
// 									array(
// 										'name' 			=> 'var',
// 										'description' 	=> 'string or array of fields in table'
// 									)
// 								)
// 							)
// 						)
// 				)
// 			);
// 		}	
		return $db_class->generate();
	}
	
	function mapper() {
	
		if(!$this->mapper)
			throw new Exception("ModelGenerator: mapper not set. Aborting.");
		
		// create zend_db_table_abstract
		$class = new Zend\Code\Generator\ClassGenerator();
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