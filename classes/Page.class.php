<?php
	include_once('Layout.class.php');
	include_once('Media.class.php');
	include_once('Section.class.php');
	include_once('MessageManager.class.php');
	
	class Page {
		private $mId;
		private $mTitle;
		private $mStandfirst;
		private $mSection;
		private $mLayout;
		private $mMedia;
		private $mPageNum;
		private $mFilename;
		private $mHeader;
		private $mContent;
		private $mFooter;
		private $mHTML;
		
		public function __construct($db, $id_)
		{
			//Update this ed to the construct id
			$this->mId = $id_;
			
			//Get page info from database
			$this->mDatabase = $db;
			
			$sql = "SELECT * FROM pages WHERE id = '$this->mId' ORDER BY pageNum ASC";			
 		
			$stmt = $this->mDatabase->prepare($sql);
			$stmt->execute();

			$Page = $stmt->fetch(PDO::FETCH_OBJ);
			
			$this->mTitle = $Page->title;
			$this->mStandfirst = $Page->standfirst;
			$this->mPageNum = $Page->pageNum;
			$this->mSection = new Section($this->mDatabase,$Page->sectionId);
			$this->mLayout = new Layout($this->mDatabase, $Page->layoutId);
			
			//Get Media from Database
			$i = 0;	
			$sql = "SELECT * FROM media WHERE pageId = '$this->mId'";  
			$stmt = $this->mDatabase->prepare($sql);
			$stmt->execute();
			
			while ($Media = $stmt->fetch(PDO::FETCH_OBJ))
			{
				//Add pages to list				
				$this->mMedia[$i] = new Media($this->mDatabase, $Media->id);
				$i++;
			}
			
			$this->mFilename = 'page'.$this->mPageNum.'.html';
			$this->mHeader = $Page->header;
			$this->mContent = $Page->mainCopy;
			$this->mFooter = $Page->footer;
		}
		public function createFrontCover($logo, $filepath)
		{
			$this->mLayout->loadTemplate();
			
			$this->mLayout->replaceVar('{Logo}', $logo);
			$this->mLayout->replaceVar('{Title}', $this->mTitle);

			//$page = $this->loadPage($filepath);
			$page = $this->mContent;
			$this->mLayout->replaceVar('{Page}', $page);

			//Any images - may be updated
			if (count($this->mMedia)>0)
			{
				foreach ($this->mMedia as $Media)
				{
					$layout = $Media->insertMedia($this->mLayout);
					$this->mLayout->replaceHTML($layout);
				}
			}
			
			$this->mLayout->publishTemplate();	
		}
		public function createContents($filepath, $Pages, $Sections)
		{
			$this->mLayout->loadTemplate();
		
			//Page Numbers and Section
			$this->mLayout->replaceVar('{Section}', $this->mSection->getName());
			$this->mLayout->replaceVar('{SectionClass}', $this->mSection->getClass());
			$this->mLayout->replaceVar('{pageNum}', $this->mPageNum);
			
			
			//$page = $this->loadPage($filepath);
			$page = $this->mContent;

			$this->mLayout->replaceVar('{Page}', $page);
			
			$this->mLayout->replaceVar('{Standfirst}', $this->mStandfirst);
			
			foreach ($Sections as $Section)
			{
				$name = preg_replace('/\s+/', '', $Section->getName()); //Strip whitespace
				$this->mLayout->replaceVar('{'.$name.'}', $this->createPagesFromSection($Section, $Pages));
			}
			//The page content
			
			/*$this->mLayout->replaceVar('{Features}', $this->createPagesFromSection($Sections[5], $Pages));
			$this->mLayout->replaceVar('{IndustryAnalysis}', $this->createPagesFromSection($Sections[6], $Pages));
			$this->mLayout->replaceVar('{MEMSNews}', $this->createPagesFromSection($Sections[7], $Pages));
			$this->mLayout->replaceVar('{ResearchReview}', $this->createPagesFromSection($Sections[8], $Pages));*/
			
			$this->mLayout->publishTemplate();	
		}
		public function createPagesFromSection($Section, $MagazinePages)
		{
			$Pages = array();
			foreach	($MagazinePages as $Page)
			{
				if ($Page->getSectionId() == $Section->getId())
					array_push($Pages, $Page);	
			}
			$output = '';
			if (count($Pages) > 0)
			{
				$output .= '<h2>'.$Section->getName().'</h2>
					<ul>
				';
				
				foreach ($Pages as $Page)
				{
					$item = $Page->getPageNum();
					//Increase pagenum by one to account for Bookblock offset
					//$item++;
					//Any images - may be updated
					
					if (substr_count($Section->getName(), 'Feature') > 0)
					{
						$media = '';
						if (count($Page->getMedia())>0)
						{
							$position = 0;
							foreach ($Page->getMedia() as $Media)
							{
								if ($Media->getPositionId() > $position)
								{ 
									$media = $Media;
									$position = $Media->getPositionId();
								}
							}
						}
						$output .= '
						<li class="feature">
							<a class="bb-nav-jumpto" data-item="'.$item.'">
								<div class="feature-title">
									<h3>'.$Page->getTitle().'</h3>
									<div class="standfirst">'.$Page->getStandfirst().'</div>
								</div>';
						if (is_object($media))
						{		
							$output .=	'<div class="focuspoint feature-image" data-focus-x="'.$media->getFocusX().'" data-focus-y="'.$media->getFocusY().'" data-focus-w="'.$media->getFocusW().'" data-focus-h="'.$media->getFocusH().'">
									'.$media->getMedia().'
									<span class="pagenum">'.$Page->getPageNum().'</span>
								</div>';
						}
						$output .= '</a>
						</li>
						';
					}
					else
						$output .= '<li><a class="bb-nav-jumpto" data-item="'.$item.'"><span class="text">'.$Page->getTitle().'</span><span class="pagenum">'.$Page->getPageNum().'</span></a></li>';			
				}
				$output .= '
					</ul>
				</li>
				';
				return $output;
			}
		}
		public function createImageEditorPreview($filepath)
		{
			$this->mLayout->loadTemplate();
			
			//Page Numbers and Section
			$this->mLayout->replaceVar('{Section}', $this->mSection->getName());
			$this->mLayout->replaceVar('{SectionClass}', $this->mSection->getClass());
			$this->mLayout->replaceVar('{pageNum}', $this->mPageNum);
			
			//Header - Usually <h2>{Header}</h2>
			if ($this->hasHeader())
				 $this->mLayout->replaceVar('{Header}', $this->loadHeader());
			
			//The page content
			//$page = $this->loadPage($filepath);
			$page = $this->mContent;
			$page = '<div class="preview-content">'.$page;
			if (!empty($this->mStandfirst))
			{
				$standfirst = '
					<div class="standfirst">'.$this->mStandfirst.'</div>
				';
				$page = $standfirst . $page;
			}
			
			$page = $page.'</div>';
			$this->mLayout->replaceVar('{Page}', $page);
			
			if ($this->mLayout->getId() != 6)
				$this->insertImageDropbox();

			//Any images - may be updated
			/*if (count($this->mMedia)>0)
			{
				foreach ($this->mMedia as $Media)
				{
					$layout = $Media->insertMediaDropbox($this->mLayout);
					$this->mLayout->replaceHTML($layout);
				}
			}*/
			
			//Footer of page - usually just a symbol and the title
			if ($this->hasFooter())
				$this->mLayout->replaceVar('{Footer}', $this->loadFooter());
			
			$this->mLayout->publishTemplate();	
		}
		public function insertImageDropbox()
		{
			if ($this->mLayout->getId() == 1)
			{
			//inline image
				$label = '{FrontCoverImage}';
				$class = 'dropbox-frontcover';
			}
			else if ($this->mLayout->getId() == 2)
			{
			//inline image
				$label = '{Contents}';
				$class = 'dropbox-contents';
			}			
			else if ($this->mLayout->getId() == 3)
			{
			//cover image
				$label = '{mediaMaster}';
				$class = 'dropbox-master';
			}
			else if ($this->mLayout->getId() == 4)
			{
			//right image
				$label = '{mediaRight}';
				$class = 'dropbox-twothirds';
			}
			else if ($this->mLayout->getId() == 5)
			{
			//right image
				$label = '{mediaRight}';
				$class = 'dropbox-half';
			}
			else if ($this->mLayout->getId() == 7)
			{
			//right image
				$label = '{fullPageAd}';
				$class = 'dropbox-fullpage';
			}
			$dropbox = '<div class="media-dropbox '.$class.'"><h3>Drop Image Here</h3></div>';
			//Any images - may be updated
			if (count($this->mMedia)>0)
			{
				foreach ($this->mMedia as $Media)
				{
					$layout = $Media->insertPreview($this->mLayout);
					$this->mLayout->replaceHTML($layout);
				}
			}
			$layout = $this->mLayout->getHTML();
			$this->mLayout->replaceHTML($layout);
			$this->mLayout->replaceVar($label , $dropbox);

		}
		public function getImageDropbox()
		{
			if ($this->mLayout->getId() == 0)
			//frontcover image
				$class = 'dropbox-frontcover';
			else if ($this->mLayout->getId() == 1)
			//inline image
				$class = 'dropbox-contents';
			else if ($this->mLayout->getId() == 2)
			//inline image
				$class = 'dropbox-contents';
			else if ($this->mLayout->getId() == 3)
			//cover image
				$class = 'dropbox-master';
			else if ($this->mLayout->getId() == 4)
			//right image
				$class = 'dropbox-twothirds';
			else if ($this->mLayout->getId() == 5)
			//right image
				$class = 'dropbox-half';
			else if ($this->mLayout->getId() == 7)
			//fullpage image
				$class = 'dropbox-fullpage';
			
			return '<div class="media-dropbox '.$class.' ui-droppable"><h3>Drop Image Here</h3></div>';
		}
		public function createPage($filepath)
		{
			$this->mLayout->loadTemplate();
			
			//Page Numbers and Section
			$this->mLayout->replaceVar('{Section}', $this->mSection->getName());
			$this->mLayout->replaceVar('{SectionClass}', $this->mSection->getClass());
			$this->mLayout->replaceVar('{pageNum}', $this->mPageNum);
			
			//Header - Usually <h2>{Header}</h2>
			if ($this->hasHeader())
				 $this->mLayout->replaceVar('{Header}', $this->loadHeader());
			
			//The page content
			//$page = $this->loadPage($filepath);
			//Content now loaded through database - $page to be changed to mContent at some point
			$page = $this->mContent;
			if (!empty($this->mStandfirst))
			{
				$standfirst = '
					<div class="standfirst">'.$this->mStandfirst.'</div>
				';
				$page = $standfirst . $page;
			}
			$this->mLayout->replaceVar('{Page}', $page);
			
			//Any images - may be updated
			if (count($this->mMedia)>0)
			{
				foreach ($this->mMedia as $Media)
				{
					$layout = $Media->insertMedia($this->mLayout);
					$this->mLayout->replaceHTML($layout);
				}
			}
			
			//Footer of page - usually just a symbol and the title
			if ($this->hasFooter())
				$this->mLayout->replaceVar('{Footer}', $this->loadFooter());
			
			$this->mLayout->publishTemplate();	
		}
		public function loadHeader()
		{
			$output = '<h2>'.$this->mTitle.'</h2>';	
			return $output;
		}
		public function loadPage($filepath)
		{
			$this->mHTML = file_get_contents($filepath.'page'.$this->mId.'.html');
			return $this->mHTML;
		}
		public function loadFooter()
		{
			
			$output = '
			<div class="footer">
            	<div class="symbol"></div>
               	<h4>'.$this->mTitle.'</h4>
            </div>
			';
			return $output;
		}
		public function updatePageNum($pageNum)
		{
			$sql = "UPDATE pages SET pageNum = :pageNum WHERE id = :id";
			$stmt = $this->mDatabase->prepare($sql);
			$stmt->execute(array(':pageNum'=>$pageNum,
			':id'=>$this->mId));
		}
		public function deleteMedia($id, $url, $filepath)
		{
			$Media = $this->getMediaFromId($id);
			$filename = explode($url, $Media->getSource());
			$image = $filepath . $filename[1];
			unlink($image);
			
			$sql = "DELETE FROM media WHERE id = :id";
			$stmt = $this->mDatabase->prepare($sql);
			$stmt->execute(array(':id'=>$id));	
			
			$i = $this->getMediaIndex($id);
			unset($this->mMedia[$i]);
			$this->mMedia = array_values($this->mMedia);
		}
		public function getId()
		{
			return $this->mId;	
		}
		public function getPageNum()
		{
			return $this->mPageNum;	
		}
		public function getTitle()
		{
			return $this->mTitle;	
		}
		public function getStandfirst()
		{
			return $this->mStandfirst;	
		}
		public function getLayoutId()
		{
			return $this->mLayout->getId();	
		}
		public function getSectionId()
		{
			return $this->mSection->getId();
		}
		public function getLayout()
		{
			return $this->mLayout;	
		}
		public function getSectionName()
		{
			return $this->mSection->getName();	
		}
		public function getSectionClass()
		{
			return $this->mSection->getClass();	
		}
		public function getContent()
		{
			return $this->mContent;
		}
		public function getFilename()
		{
			return $this->mFilename;
		}
		public function getFilenameNoExt()
		{
			$filename = explode(".", $this->mFilename);
			return $filename[0];
		}
		public function getMedia()
		{
			return $this->mMedia;	
		}
		public function getMediaFromId($id)
		{
			foreach ($this->mMedia as $Media)
			{
				if ($Media->getId() == $id)
					return $Media;	
			}
		}
		public function getMediaIndex($id)
		{
			$i = 0;
			foreach ($this->mMedia as $Media)
			{
				if ($Media->getId() == $id)
					return $i;
				$i++;	
			}
		}
		public function getMediaOfPosition($position)
		{
			if (count($this->mMedia) > 0)
			{
				foreach ($this->mMedia as $Media)
				{
					if ($Media->getPositionId() == $position)
						return $Media;
				}
			}
		}
		public function hasHeader()
		{
			return $this->mHeader;	
		}
		public function hasFooter()
		{
			return $this->mFooter;	
		}
	}
?>