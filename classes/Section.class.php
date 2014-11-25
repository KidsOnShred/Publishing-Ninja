<?php	
	//Is this class overkill you ask? Answer: FUCK YOU!!
	class Section {
		private $mId;
		private $mMagazineId;
		private $mName;
		private $mColor;
		private $mSubmenu;
		
		public function __construct($db, $id)
		{
			//Update this ed to the construct id
			$this->mId = $id;
			
			//Get page info from database
			$this->mDatabase = $db;


			$sql = "SELECT * FROM sections WHERE id = '$this->mId'";			
 		
			$stmt = $this->mDatabase->prepare($sql);
			$stmt->execute();

			$Section = $stmt->fetch(PDO::FETCH_OBJ);
			
			if (is_object($Section))
			{
				$this->mName = $Section->name;
				$this->mMagazineId = $Section->magazineId;
				$this->mColor = $Section->colorHex;
				$this->mSubmenu = $Section->submenu;
			}
		}
		public function updateSectionOrder($index)
		{
			$sql = "UPDATE sections SET sectionOrder = :sectionOrder WHERE id = :id";
			$stmt = $this->mDatabase->prepare($sql);
			$stmt->execute(array(':sectionOrder'=>$index,
			':id'=>$this->mId));	
		}
		public function getId()
		{
			return $this->mId;	
		}
		public function getMagazineId()
		{
			return $this->mMagazineId;	
		}
		public function getName()
		{
			return $this->mName;	
		}
		public function getColor()
		{
			return $this->mColor;	
		}
		public function getClass()
		{
			$class = 'style="background-color:#'.$this->mColor.';"';
			return $class;	
		}
		public function hasSubmenu()
		{
			return $this->mSubmenu;	
		}
	}
?>