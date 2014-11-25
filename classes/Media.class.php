<?php	
	class Media {
		private $mId;
		private $mPageId;
		private $mTypeId;
		private $mPositionId;
		private $mSource;
		private $mLink;
		private $mFocusX;
		private $mFocusY;
		private $mFocusW;
		private $mFocusH;
		private $mActive;
		
		public function __construct($db, $id)
		{
			//Update this ed to the construct id
			$this->mId = $id;
			//Get page info from database
			$this->mDatabase = $db;
			
			$sql = "SELECT * FROM media WHERE id = '$this->mId'";			
			$stmt = $this->mDatabase->prepare($sql);
			$stmt->execute();

			$Media = $stmt->fetch(PDO::FETCH_OBJ);
			
			$this->mPageId = $Media->pageId;
			$this->mTypeId = $Media->typeId;
			$this->mPositionId = $Media->positionId;
			$this->mSource = $Media->source;
			$this->mLink = $Media->href;
			$this->mFocusX = $Media->focusX;
			$this->mFocusY = $Media->focusY;
			$this->mFocusW = $Media->focusW;
			$this->mFocusH = $Media->focusH;
			$this->mActive = $Media->active;
		}
		public function insertMedia($Layout)
		{
			if ($this->getPositionId() == 0)
			{
			//inline image
				$label = '{FrontCoverImage}';
			}
			else if ($this->getPositionId() == 1)
			{
			//inline image
				$label = '{Contents}';
			}
			else if ($this->getPositionId() == 2)
			{
			//inline image
				$label = '{Media'.$this->mId.'}';
			}
			else if ($this->getPositionId() == 3)
			{
			//cover image
				$label = '{mediaMaster}';
			}
			else if ($this->getPositionId() == 4 || $this->getPositionId() == 5)
			{
			//right image
				$label = '{mediaRight}';
			}
			else if ($this->getPositionId() == 7)
			{
			//right image
				$label = '{fullPageAd}';
			}
			$Layout->replaceVar($label , $this->getMedia());
			return $Layout->getHTML();
		}
		public function insertPreview($Layout)
		{
			if ($this->getPositionId() == 0)
			{
			//inline image
				$label = '{FrontCoverImage}';
			}
			else if ($this->getPositionId() == 1)
			{
			//inline image
				$label = '{Contents}';
			}
			else if ($this->getPositionId() == 2)
			{
			//inline image
				$label = '{Media'.$this->mId.'}';
			}
			else if ($this->getPositionId() == 3)
			{
			//cover image
				$label = '{mediaMaster}';
			}
			else if ($this->getPositionId() == 4 || $this->getPositionId() == 5)
			{
			//right image
				$label = '{mediaRight}';
			}
			else if ($this->getPositionId() == 7)
			{
			//right image
				$label = '{fullPageAd}';
			}
			$Layout->replaceVar($label , $this->getPreviewMedia());
			return $Layout->getHTML();
		}
		public function setPosition($positionId)
		{
			$this->mPositionId = $positionId;
			$sql = "UPDATE media SET positionId = :positionId WHERE id = :id";  
			$stmt = $this->mDatabase->prepare($sql);
			$stmt->execute(array(
				':positionId'=>$this->mPositionId,
				':id'=>$this->mId
			));
		}
		public function updateLink($link)
		{
			$this->mLink = $link;
			$sql = "UPDATE media SET href = :link WHERE id = :id";  
			$stmt = $this->mDatabase->prepare($sql);
			$stmt->execute(array(
				':link'=>$this->mLink,
				':id'=>$this->mId
			));
		}
		public function activate()
		{
			$this->mActive = 1;
			$sql = "UPDATE media SET active = '1' WHERE id = :id";  
			$stmt = $this->mDatabase->prepare($sql);
			$stmt->execute(array(
				':id'=>$this->mId
			));
		}
		public function deactivate()
		{
			$this->mActive = 0;
			$sql = "UPDATE media SET active = '0', positionId = '1' WHERE id = :id";  
			$stmt = $this->mDatabase->prepare($sql);
			$stmt->execute(array(
				':id'=>$this->mId
			));
		}
		public function getMedia()
		{
			if ($this->getTypeId() == 0)
			{
			//Get image
				$html = '';
				if (!empty($this->mLink))
					$html .= '<a href="'.$this->mLink.'" target="_blank">';
				$html .= '<img src="'.$this->mSource.'" />';
				if (!empty($this->mLink))
					$html .= '</a>';
			}
			else if ($this->getTypeId() == 1)
			{
			//Get figure
			}
			else if ($this->getTypeId() == 2)
			{
			//Get HTML
			}
			else if ($this->getTypeId() == 3)
			{
			//Get Video
			}
			else
				$html = '<p>No Media Found.</p>';
			return $html;
		}
		public function getPreviewMedia()
		{
			if ($this->getTypeId() == 0)
			{
			//Get image
				$html = '<div class="img-default img-active" id="img-'.$this->mId.'">
					<img src="'.$this->mSource.'?t='.mktime().'" />
					<div class="img-controls btn-group btn-group-xs">
						<a class="btn btn-moveimg btn-primary" role="button"><i class="fa fa-arrows"></i></a>
						<a class="btn btn-resizeimg btn-primary" role="button"><i class="fa fa-expand"></i></a>
						<a class="btn btn-showcrop btn-primary" role="button"><i class="fa fa-crop"></i></a>
						<a class="btn btn-caption btn-primary" role="button"><i class="fa fa-comment"></i></a>
						<a class="btn btn-addlink btn-primary" role="button"><i class="fa fa-link"></i></a>
					</div>
					<div class="img-delete btn-group btn-group-xs">
						<a class="btn btn-removeimg btn-danger" role="button"><i class="fa fa-level-up"></i></a>
					</div>
				</div>';
			}
			else if ($this->getTypeId() == 1)
			{
			//Get figure
			}
			else if ($this->getTypeId() == 2)
			{
			//Get HTML
			}
			else if ($this->getTypeId() == 3)
			{
			//Get Video
			}
			else
				$html = '<p>No Media Found.</p>';
			return $html;
		}
		public function getId()
		{
			return $this->mId;	
		}
		public function getPageId()
		{
			return	$this->mPageId;
		}
		public function getTypeId()
		{
			return $this->mTypeId;	
		}
		public function getPositionId()
		{
			return $this->mPositionId;	
		}
		public function getSource()
		{
			return $this->mSource;	
		}
		public function getLink()
		{
			return $this->mLink;
		}
		public function getFocusX()
		{
			return $this->mFocusX;
		}
		public function getFocusY()
		{
			return $this->mFocusY;
		}
		public function getFocusW()
		{
			return $this->mFocusW;
		}
		public function getFocusH()
		{
			return $this->mFocusH;
		}
		public function isActive()
		{
			return $this->mActive;	
		}
		
	}
?>