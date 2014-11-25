<?php	
	include_once('MessageManager.class.php');

	class Layout {
		private $mId;
		private $mDatabase;
		private $mFile;
		private $mHTML;
		private $mName;
		private $mImage;
		
		public function __construct($db, $id)
		{
			$this->mId = $id;
			//Get page info from database
			$this->mDatabase = $db;

			$sql = "SELECT * FROM layouts WHERE id = '$this->mId' LIMIT 1";			
 		
			$stmt = $this->mDatabase->prepare($sql);
			$stmt->execute();

			$Layout = $stmt->fetch(PDO::FETCH_OBJ);
			$this->mName = $Layout->name;
			$this->mImage = $Layout->image;
			$this->mFile = '/home/ninja/public_html/publisher/magazine/layouts/'.$this->mId.'.html';
		}
		public function loadTemplate()
		{
			$this->mHTML = file_get_contents($this->mFile);	
		}
		public function replaceHTML($html)
		{
			$this->mHTML = $html;	
		}
		public function replaceVar($label, $var)
		{
			$this->mHTML = str_replace("$label", $var, $this->mHTML);
		}
		public function publishTemplate()
		{
			eval("?>".$this->mHTML."<?");	
		}
		public function getId()
		{
			return $this->mId;	
		}
		public function getName()
		{
			return $this->mName;	
		}
		public function getImage()
		{
			//Will update when getting to proper domain
			return '/publisher'.$this->mImage;	
		}
		public function getHTML()
		{
			return $this->mHTML;	
		}
	}
?>