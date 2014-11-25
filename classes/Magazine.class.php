<?php
	include_once('Page.class.php');
	include_once('Section.class.php');
	
	class Magazine {
		private $mDatabase;
		private $mId;
		private $mPublicationId;
		private $mIssue;
		private $mName;
		private $mYear;
		private $mPages;
		private $mSections;
		private $mLayout;
		private $mFilepath;
		private $mImageFilepath;
		private $mImageURL;
		
		public function __construct($core_, $id)
		{
			//Set this object to connect the virtual database
			$this->mDatabase = $core_->mDb;
			
			$sql = "SELECT * FROM magazines WHERE id = '$id' LIMIT 1";  
			$stmt = $this->mDatabase->prepare($sql);
			$stmt->execute();
			
			$Magazine = $stmt->fetch(PDO::FETCH_OBJ);
			
			$this->mId = $Magazine->id;
			$this->mPublicationId = $Magazine->publicationId;
			$this->mIssue = $Magazine->issue;
			$this->mName = $Magazine->issue;
			$this->mYear = $Magazine->year;
			
			$this->loadPagesFromDb();
			
			//Get Sections from Database
			$i = 0;	
			$sql = "SELECT * FROM sections WHERE magazineId = '0' OR magazineId = '$this->mId' ORDER BY magazineId, sectionOrder ASC";  
			
			$stmt = $this->mDatabase->prepare($sql);
			$stmt->execute();
			
			while ($Section = $stmt->fetch(PDO::FETCH_OBJ))
			{
				//Add pages to list				
				$this->mSections[$i] = new Section($this->mDatabase, $Section->id);
				$i++;
			}
			
			$this->mFilepath = '/home/ninja/public_html/publisher/magazine/templates/'.$this->mPublicationId.'/'.$this->mYear.'/'.$this->mId.'/';
			$this->mImageFilepath = '/home/ninja/public_html/publisher/content/img/';
			$this->mImageURL = 'http://www.publishing.ninja/publisher/content/img/';
			
			if (!empty($_GET['magazineId']))
				$_SESSION['magazineId'] = $_GET['magazineId'];
			
		}
		public function loadPagesFromDb()
		{
			//Get Pages from Database
			$i = 0;	
			$sql = "SELECT * FROM pages WHERE magazineId = '$this->mId' ORDER BY pageNum ASC";  
			$stmt = $this->mDatabase->prepare($sql);
			$stmt->execute();
			
			while ($Page = $stmt->fetch(PDO::FETCH_OBJ))
			{
				//Add pages to list				
				$this->mPages[$i] = new Page($this->mDatabase, $Page->id);
				$i++;
			}	
		}
		public function getLogo()
		{
			//Get Pages from Database
			$i = 0;	
			$sql = "SELECT logo FROM publications WHERE id = '$this->mPublicationId'";  
			$stmt = $this->mDatabase->prepare($sql);
			$stmt->execute();
			
			$Pub = $stmt->fetch(PDO::FETCH_OBJ);
			
			return $Pub->logo;
		}
		public function createImagePreviewPage($pageNum)
		{
			$this->mPages[$pageNum]->createImageEditorPreview($this->mFilepath);
		}
		public function createPage($pageNum)
		{
			if ($this->mPages[$pageNum]->getLayoutId() == 1)
				$this->mPages[$pageNum]->createFrontCover($this->getLogo(), $this->mFilepath);
			else if ($this->mPages[$pageNum]->getLayoutId() == 2)
				$this->mPages[$pageNum]->createContents($this->mFilepath, $this->mPages, $this->mSections);
			else
				$this->mPages[$pageNum]->createPage($this->mFilepath);
		}
		public function createMenu()
		{
			if (count($this->mSections) > 0)
			{
				$output = '
				<ul>
				';
				foreach ($this->mSections as $Section)
				{
					
					if ($Section->hasSubmenu())
					{
						$Pages = $this->getPagesFromSection($Section->getId()); 
						if (count($Pages) > 0)
						{
							$output .= '<li class="submenu"><a><span class="text">'.$Section->getName().'</span><span class="pagenum"><div class="arrow-sprite arrow-forward"></div></span></a>
								<ul>
							';
							
							foreach ($Pages as $Page)
							{
								$item = $Page->getPageNum();
								//Increase pagenum by one to account for Bookblock offset
								//$item++;
								$section = '';
								if ($Section->getName() != $Page->getTitle())
								{
									$section = '<span class="section">'.$Section->getName().'</span>';
								}
								$output .= '<li><a class="bb-nav-jumpto" data-item="'.$item.'">'.$section.'<span class="text">'.$Page->getTitle().'</span><span class="pagenum">'.$Page->getPageNum().'</span></a></li>';
							}
							$output .= '
								</ul>
							</li>
							';
						}
					}
					else
					{
						$Pages = $this->getPagesFromSection($Section->getId()); 
						
						if (count($Pages) > 0)
						{
							foreach ($Pages as $Page)
							{
								if ($Page->getPageNum() > 0)
									$pageNum = $Page->getPageNum();
								else
									$pageNum ='';
								$item = $Page->getPageNum();
								//Increase pagenum by one to account for Bookblock offset
								//$item++;
								$section = '';
								if ($Section->getName() != $Page->getTitle())
								{
									$section = '<span class="section">'.$Section->getName().'</span>';
								}
								$output .= '<li><a class="bb-nav-jumpto" data-item="'.$item.'">'.$section.'<span class="text">'.$Page->getTitle().'</span><span class="pagenum">'.$pageNum.'</span></a></li>';
							}
						}
					}
				}
				$output .= '
				</ul>
				';
			}
			else
				echo '<p>No pages found.</p>';
			return $output;
		}
		public function createPages()
		{
			$output = '<div class="container">
			<div class="bb-custom-wrapper">
				<div id="bb-bookblock" class="bb-bookblock">
				<noscript>You need javascript to run this web application. Please enable javascript to continue.</noscript>';
					for ($i=0; $i<$this->getPagesCount(); $i++)
					{
						$output .= '<div class="bb-item">
							<div id="loading'.$i.'" class="loading">
							<div id="floatingCirclesG">
								<div class="f_circleG" id="frotateG_01">
								</div>
								<div class="f_circleG" id="frotateG_02">
								</div>
								<div class="f_circleG" id="frotateG_03">
								</div>
								<div class="f_circleG" id="frotateG_04">
								</div>
								<div class="f_circleG" id="frotateG_05">
								</div>
								<div class="f_circleG" id="frotateG_06">
								</div>
								<div class="f_circleG" id="frotateG_07">
								</div>
								<div class="f_circleG" id="frotateG_08">
								</div>
							</div>
							</div>
							<div class="content" id="ajax-p'.$i.'"></div>
						</div>';		
					}
				$output .= '
				</div>
				<div class="svg-wrap">
					<svg width="64" height="64" viewBox="0 0 64 64">
						<path id="arrow-left-1" d="M46.077 55.738c0.858 0.867 0.858 2.266 0 3.133s-2.243 0.867-3.101 0l-25.056-25.302c-0.858-0.867-0.858-2.269 0-3.133l25.056-25.306c0.858-0.867 2.243-0.867 3.101 0s0.858 2.266 0 3.133l-22.848 23.738 22.848 23.738z" />
					</svg>
					<svg width="64" height="64" viewBox="0 0 64 64">
						<path id="arrow-right-1" d="M17.919 55.738c-0.858 0.867-0.858 2.266 0 3.133s2.243 0.867 3.101 0l25.056-25.302c0.858-0.867 0.858-2.269 0-3.133l-25.056-25.306c-0.858-0.867-2.243-0.867-3.101 0s-0.858 2.266 0 3.133l22.848 23.738-22.848 23.738z" />
					</svg>
				</div>
				<nav class="nav-slide">
					<a id="bb-nav-prev" class="arrow-prev prev">
						<span class="icon-wrap hide-mobile"><svg class="icon" width="32" height="32" viewBox="0 0 64 64"><use xlink:href="#arrow-left-1"></svg></span>
						<div class="hide-mobile">
							<h3>Previous Story Title</h3>
							<img src="http://www.angel-test.net/publisher/content/img/thumbnails/thumb-18-147.png?t=1401438126" class="nav-previous-thumb" alt="Previous thumb"/>
						</div>
					</a>
					<a id="bb-nav-next" class="arrow-next next">
						<span class="icon-wrap hide-mobile"><svg class="icon" width="32" height="32" viewBox="0 0 64 64"><use xlink:href="#arrow-right-1"></svg></span>
						<div class="hide-mobile">
							<h3>Next Story Title</h3>
							<img src="http://www.angel-test.net/publisher/content/img/thumbnails/thumb-18-149.png?t=1401438126" class="nav-next-thumb" alt="Next thumb"/>
						</div>
					</a>
				</nav>
			</div>
		</div><!-- /container -->';	
			/*<nav>
					<a id="bb-nav-prev" class="arrow-prev"><img src="/publisher/magazine/images/arrow-prev.png" /></a>
					<a id="bb-nav-next" class="arrow-next"><img src="/publisher/magazine/images/arrow-next.png" /></a>
				</nav>*/
			return $output;
		}
		public function insertPage($id)
		{
			array_push($this->mPages, new Page($this->mDatabase, $id));	
		}
		public function insertSection($id)
		{
			array_push($this->mSections, new Section($this->mDatabase, $id));	
		}
		public function updateSection($id)
		{
			$this->mSections[$this->getSectionIndex($id)] = new Section($this->mDatabase, $id);
		}
		public function deleteSection($id)
		{
			unset($this->mSections[$this->getSectionIndex($id)]);
			$this->mSections = array_values($this->mSections);
			$i = 0;
			foreach ($this->mSections as $Section)
			{
				$Section->updateSectionOrder($i);
				$i++;
			}
		}
		public function deletePage($id)
		{
	
			$sql = "DELETE FROM pages WHERE id = :id";
			$stmt = $this->mDatabase->prepare($sql);
			$stmt->execute(array(':id'=>$id));
			
			$file = $this->getFilepath().'page'.$id.'.html';
			unlink($file);
			$pageNum = $this->getPageNum($id);
			unset($this->mPages[$pageNum]);
			$this->mPages = array_values($this->mPages);
			$i = 0;
			foreach ($this->mPages as $Page)
			{
				$Page->updatePageNum($i);
				$i++;
			}
		}
		public function delete()
		{
			$sql = "DELETE FROM magazines WHERE id = :id";
			$stmt = $this->mDatabase->prepare($sql);
			$stmt->execute(array(':id'=>$this->mId));
		}
		public function convertToMd5($string)
		{
			return md5($string . self::MD5_SALT);
		}
		public function getId()
		{
			return $this->mId;	
		}
		public function getPublicationId()
		{
			return $this->mPublicationId;	
		}
		public function getIssue()
		{
			return $this->mIssue;	
		}
		public function getName()
		{
			//Issue will be depricated soon use name
			return $this->mName;	
		}
		public function getYear()
		{
			return $this->mYear;	
		}
		public function getPageTitle($pageNum)
		{
			return $this->mPages[$pageNum]->getTitle();
		}
		public function getPreviousNavigation($pageNum)
		{
			if (@is_object($this->mPages[$pageNum-1])) {
				$Page = $this->mPages[$pageNum-1];
				$image = '/publisher/content/img/thumbnails/thumb-'.$this->mId.'-'.$Page->getId().'.png?t='.mktime();
				$title = $Page->getTitle();
				return $title.'::'.$image;
			}			
		}
		public function getNextNavigation($pageNum)
		{
			if (@is_object($this->mPages[$pageNum+1])) {
				$Page = $this->mPages[$pageNum+1];
				$image = '/publisher/content/img/thumbnails/thumb-'.$this->mId.'-'.$Page->getId().'.png?t='.mktime();
				$title = $Page->getTitle();
				return $title.'::'.$image;
			}			
		}
		public function getPagesCount()
		{
			return count($this->mPages);	
		}
		public function getFrontCover()
		{
			foreach ($this->mPages as $Page)
			{
				if ($Page->getLayoutId() == 1)
					return '<img src="/publisher/content/img/thumbnails/thumb-'.$this->mId.'-'.$Page->getId().'.png?t='.mktime().'" />';
			}
		}
		public function getPages()
		{
			return $this->mPages;	
		}
		public function getPage($pageNum)
		{
			return $this->mPages[$pageNum];
		}
		public function getPageNum($id)
		{
			if (!empty($this->mPages))
			{
				foreach ($this->mPages as $Page)
				{
					if ($Page->getId() == $id)
						return $Page->getPageNum();	
				}
			}
		}
		public function getIdFromPageNum($pageNum)
		{
			foreach ($this->mPages as $Page)
			{
				if ($Page->getPageNum() == $pageNum)
					return $Page->getId();
			}
		}
		public function getPagesFromSection($section)
		{
			$Pages = array();
			foreach	($this->getPages() as $Page)
			{
				if ($Page->getSectionId() == $section)
					array_push($Pages, $Page);	
			}
			return $Pages;
		}
		public function getMediaFromPage($pageId)
		{
			$Page = $this->getPage($this->getPageNum($pageId));
			return $Page->getMedia();
		}
		public function getSections()
		{
			return $this->mSections;	
		}
		public function getSectionIndex($id)
		{
			$i = 0;
			foreach ($this->mSections as $Section)
			{
				if ($Section->getId() == $id)
					return $i;
				$i++;
			}
			return NULL;
		}
		public function getCustomSections()
		{
			$CustomSections = array();
			foreach ($this->mSections as $Section)
			{
				if ($Section->getMagazineId() == $this->mId)
					array_push($CustomSections, $Section);
			}
			return $CustomSections;
		}
		public function getFilepath()
		{
			return $this->mFilepath;	
		}
		public function getImageFilepath()
		{
			return $this->mImageFilepath;	
		}
		public function getImageURL()
		{
			return $this->mImageURL;	
		}
		/*=== Private Functions === */
		private function encrypt($string)
		{
			$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
    		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
			$block = mcrypt_get_block_size('des', 'ecb');
			$pad = $block - (strlen($string) % $block);
			$string .= str_repeat(chr($pad), $pad);
			return mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->mEncryptKey, $string, MCRYPT_MODE_ECB, $iv);
		}		
		private function decrypt($string)
		{   
			$string = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->mEncryptKey, $string, MCRYPT_MODE_ECB);
		
			$block = mcrypt_get_block_size('des', 'ecb');
			$pad = ord($string[($len = strlen($string)) - 1]);
			return substr($string, 0, strlen($string) - $pad);
		}
		private function cleanUpURL($url)
		{
			$url = htmlentities($url, ENT_QUOTES);
			$url = str_ireplace(" ", "-", trim($url));
			$url = str_ireplace("%", "", $url); 
			$url = str_ireplace("?", "", $url);
			$url = str_ireplace('/', '', $url);
			$url = str_ireplace('&amp;', '-and-', $url);
			$url = preg_replace('/[\W]+/', '-', $url);
			$url = trim($url, "-");
			$url = strtolower($url);
			return $url;	
		}
	}
?>