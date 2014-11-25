<?php
	include_once('Magazine.class.php');
	include_once('Section.class.php');
	include_once('MessageManager.class.php');
	
	class Publication {
		private $mCore;
		private $mDatabase;
		private $mId;
		private $mName;
		private $mLogo;
		private $mURL;
		private $mMagazines;
		
		public function __construct($core_, $id_)
		{
			//Set this object to connect the virtual database
			$this->mCore = $core_;
			
			$this->mDatabase = $core_->mDb;
			
			$this->mId = $id_;
			
			$sql = "SELECT * FROM publications WHERE id = '$id_'";  
			$stmt = $this->mDatabase->prepare($sql);
			$stmt->execute();
			
			$Pub = $stmt->fetch(PDO::FETCH_OBJ);
			
			$this->mName = $Pub->name;
			$this->mLogo = $Pub->logo;
			$this->mURL = $Pub->url;
			
			$sql = "SELECT * FROM magazines WHERE publicationId = '$id_' ORDER BY year, issue DESC";  
			$stmt = $this->mDatabase->prepare($sql);
			$stmt->execute();
			
			$i = 0;
			while ($Magazine = $stmt->fetch(PDO::FETCH_OBJ))
			{
				$this->mMagazines[$i] = new Magazine($core_, $Magazine->id);
				$i++;
			}			
		}
		public function init()
		{
			if ($_SERVER['REQUEST_METHOD'] == 'POST')
			{
				if ($_POST['action'] == 'AddMagazine')
				{
					$this->createMagazine();
					$this->listMagazines();
				}
				else if ($_POST['action'] == 'EditMagazine')
				{
					$this->editMagazine();	
				}
				else if ($_POST['action'] == 'DeleteMagazine')
				{
					$this->deleteMagazine();
				}
				else if ($_POST['action'] == 'DisplayMagazines')
				{
					echo $this->listMagazines();
				}
				else if ($_POST['action'] == 'DisplayAddMagazine')
				{
					$this->displayEditor(0);
				}
				else if ($_POST['action'] == 'DisplayEditMagazine')
				{
					$this->displayEditor(1);
				}
				else if ($_POST['action'] == 'DisplayDeleteMagazine')
				{
					$this->displayDeleteMagazine();
				}
				else if ($_POST['action'] == 'DisplayAddSection')
				{
					$this->displayEditSection(0);
				}
				else if ($_POST['action'] == 'DisplayEditSection')
				{
					$this->displayEditSection(1);
				}
				else if ($_POST['action'] == 'AddSection')
				{
					$this->addSection();
				}
				else if ($_POST['action'] == 'EditSection')
				{
					$this->editSection();
				}
				else if ($_POST['action'] == 'DeleteSection')
				{
					$this->deleteSection();
				}
				else if ($_POST['action'] == 'ReorderSections')
				{
					$this->reorderSections($_POST['sections']);
				}
				else 
				{
					MessageManager::displayMessage('You posted a form but I couldn\'t understand what you wanted me to do. The action you are trying to perform probably isn\'t available yet.<p>Action: '.$_POST['action'].'</p>');	
				}
			}
			else if ($_SERVER['REQUEST_METHOD'] == 'GET')
			{
				if (!empty($_GET['action']))
				{
					if ($_GET['action'] == 'add')
						$this->displayEditor(0);
					else if ($_GET['action'] == 'edit')
						$this->displayEditor(1);
				}
				else
					echo $this->listMagazines();
			}
			else
				echo $this->listMagazines();
		}
		public function displayOptions()
		{
			$output = '
			<div class="row">
				<div class="col-xs-2">
					<a class="btn btn-default btn-action" data-action="DisplayAddMagazine" data-publicationid="'.$this->mId.'">Add Magazine</a>
				</div>
			</div>
			';
			return $output;	
		}
		public function listMagazines()
		{
			$output = $this->displayOptions();
			$output .= '
			<div class="table-responsive">
				<table class="table table-striped">
					<thead>
						<tr>
							<th></th>
							<th>Year</th>
							<th>Issue</th>
							<th></th>
							<th></th>
							<th></th>
						</tr>
					</thead>
					<tbody>
				';
			if ($this->getMagazines())
			{	
				foreach ($this->mMagazines as $Magazine)
				{	
					$output .= '
					<tr>
						<td><i class="fa fa-book"></i></td>
						<td>'.$Magazine->getYear().'</td>
						<td>'.$Magazine->getIssue().'</td>
						<td><a href="/publisher/magazine/'.$Magazine->getId().'/" target="_blank"><i class="fa fa-eye"></i></a></td>
						<td><a class="btn-action" data-action="DisplayEditMagazine" data-publicationid="'.$this->mId.'" data-magazineid="'.$Magazine->getId().'"><i class="fa fa-gear"></i></a></td>
						<td><a class="btn-action" data-action="DisplayDeleteMagazine" data-publicationid="'.$this->mId.'" data-magazineid="'.$Magazine->getId().'"><i class="fa fa-trash-o"></i></a></td>
						<td><a href="/publisher/issue/'.$Magazine->getId().'/"><i class="fa fa-edit"></i></a></td>
					</tr>
					';
					
				}
			}
				$output .= '
					</tbody>
            	</table>
			</div>';
			
			return $output;
		}
		public function displayEditor($mode)
		{
			if ($mode)
			{
				$id = $this->getMagazineId();
				$Magazine = $this->getMagazine($id);
				$issue = $Magazine->getIssue();
				$year = $Magazine->getYear();
			}
			else
			{
				$issue = count($this->getMagazines());
				$issue++;
			}
			echo '
			<form action="'.$_SERVER['REQUEST_URI'].'" method="post" enctype="multipart/form-data" class="editor-form">
				<div class="row editor-issue">
					<h4>Issue Number</h4>
					<p class="help-text">Which issue are you creating?</p>
					<input type="text" class="issue" name="issue" value="'.@$issue.'" />
				</div>
				<div class="row editor-year">
					<h4>Year</h4>
					<p class="help-text">What year is it going to be released?.</p>
					<select name="year">
						<option value="2014" selected>2014</option>
						<option value="2013">2013</option>
						<option value="2012">2012</option>
						<option value="2011">2011</option>
						<option value="2010">2010</option>
					</select>
				</div>
				'.$this->getMagazineSections(@$id).'
				<input type="hidden" name="magazineId" value="'.@$id.'" />
				<input type="hidden" id="publicationId" name="publicationId" value="'.$this->getPublicationId().'" />';
				if ($mode == 0)
					echo '<input type="hidden" id="action" name="action" value="AddMagazine" />';
				else
					echo '<input type="hidden" id="action" name="action" value="EditMagazine" />';
				echo '
				
			</form>
			<script type="text/javascript">
				$(function() {
					 $( ".sections" ).sortable();
				});
			</script>
			';	
			/*to be deleted?
			<div class="row editor-submit">';
				if ($mode == 0)
					echo '<h4>Add Magazine</h4><input class="btn btn-success" type="submit" name="action" id="submitForm" value="Add" />';
				else
					echo '<h4>Finish Editing</h4><input class="btn btn-success" type="submit" name="action" id="submitForm" value="Edit" />';
				echo '
				</div>
			*/
		}
		public function displayEditSection($mode)
		{
			if ($mode)
			{
				$Section = new Section($this->mDatabase, $_POST['sectionId']);
				$id = $Section->getId();
				$name = $Section->getName();
				$color = $Section->getColor();
				if ($Section->hasSubmenu())
					$submenu = 'checked';
				else 
					$submenu = '';
			}
			
			echo '
				<h4>Section Name</h4>
				<form>
				<input type="text" name="name" value="'.@$name.'" />
				<h4>Color</h4>
				<input type="text" name="color" value="'.@$color.'" />
				<h4>Do you want to group stories together in the contents?</h4>
				<input type="checkbox" name="submenu" '.@$submenu.' />
				<input type="hidden" name="id" value="'.@$id.'" />	
				</form>
			';
		}
		public function addSection()
		{
			$data = $this->unserializePOST($_POST['data']);

			$magazineId = $this->getMagazineId();
			$name = $data['name'];
			if (!empty($data['color']))
				$color = $data['color'];
			else
				$color = 'CCCCCC';
			if(!empty($data['submenu']))
				$submenu = 1;
			else
				$submenu = 0;
				
			$i = 0;	
			$sql = "SELECT sectionOrder FROM sections WHERE magazineId = '$magazineId' ORDER BY sectionOrder DESC LIMIT 1";  
			$stmt = $this->mDatabase->prepare($sql);
			$stmt->execute();
			

			$Section = $stmt->fetch(PDO::FETCH_OBJ);
			if (is_object($Section))
			{
				$sectionOrder = $Section->sectionOrder;
				$sectionOrder++;	
			}
			else
				$sectionOrder = 0;
					
			$sql = "INSERT INTO sections (magazineId, sectionOrder, name, colorHex, submenu) VALUES (:magazineId, :sectionOrder, :name, :colorHex, :submenu)";  
			$stmt = $this->mDatabase->prepare($sql);
			$stmt->execute(array(':magazineId'=>$magazineId,
				':sectionOrder'=>$sectionOrder,
				':name'=>$name,
				':colorHex'=>$color,
				':submenu'=>$submenu));
			
			
			$this->mMagazines[$this->getMagazineIndex($magazineId)]->insertSection($this->mDatabase->lastInsertId());
			
			MessageManager::displayMessage('New section added.', 1);
			$this->displayEditor(1);
		}
		public function editSection()
		{
			$data = $this->unserializePOST($_POST['data']);
			$magazineId = $_POST['magazineId'];
			$id = $data['id'];
			$name = $data['name'];
			$color = $data['color'];
			if(!empty($data['submenu']))
				$submenu = 1;
			else
				$submenu = 0;
		
			$sql = "UPDATE sections SET name = :name, colorHex = :color, submenu = :submenu WHERE id = :id";  
			
			$stmt = $this->mDatabase->prepare($sql);
			$stmt->execute(array(
				':name'=>$name,
				':color'=>$color,
				':submenu'=>$submenu,
				':id'=>$id
			));	
			
			$this->mMagazines[$this->getMagazineIndex($magazineId)]->updateSection($id);
			MessageManager::displayMessage('Section updated.', 1);
			$this->displayEditor(1);
		}
		public function deleteSection()
		{
			$id = $_POST['sectionId'];
			$magazineId = $_POST['magazineId'];
			
			$sql = "DELETE FROM sections WHERE id = :id";
			$stmt = $this->mDatabase->prepare($sql);
			$stmt->execute(array(':id'=>$id));
			
			$this->mMagazines[$this->getMagazineIndex($magazineId)]->deleteSection($id);
			MessageManager::displayMessage('Section deleted.',1);
			$this->displayEditor(1);
		}
		public function getMagazineSections($magazineId = NULL)
		{
			$output = '<div class="row editor-section">
				<h4>Edit Sections</h4>
				<p class="help-text">These are the page sections which will display in the contents</p>
				<div class="sections">';
			if (!empty($magazineId)) {
				$Magazine = $this->getMagazine($magazineId);
				if (count($Magazine->getCustomSections()) > 0)
				{
					foreach ($Magazine->getCustomSections() as $Section)
					{
						$style = 'background-color:#'.$Section->getColor().';';
						if (hexdec($Section->getColor()) < 10066329)
							$style .= 'color:#fff;';
						else
							$style .= 'color:#121212;';
							
						$output .= '<div id="'.$Section->getId().'" class="btn-group"><a class="btn" style="'.$style.'" >'.$Section->getName().'</a><a class="btn btn-danger btn-deletesection" id="'.$Section->getId().'" data-magazineid="'.$magazineId.'"><i class="fa fa-trash-o"></i></a><a class="btn btn-primary btn-editsection" id="'.$Section->getId().'"><i class="fa fa-edit"></i></a></div>';	
					}
				}
				else
					$output .= '<p>No sections found.</p>';
			}
			else
			{
				$output .= '<p>No sections found.</p>';
			}
			$output .= '</div>
				<a class="btn btn-success btn-section">Add Section</a>
			</div>';
			return $output;
		}
		public function createMagazine()
		{
			$data = $this->unserializePOST($_POST['data']);
			$issue = $data['issue'];
			$year = $data['year'];
			
			$sql = "INSERT INTO magazines (publicationId, issue, year) VALUES (:publicationId, :issue, :year)";  
			$stmt = $this->mDatabase->prepare($sql);
			$stmt->execute(array(':publicationId'=>$this->mId,
				':issue'=>$issue,
				':year'=>$year));
			$id = $this->mDatabase->lastInsertId();
			
			if (empty($this->mMagazines))
				$this->mMagazines = array();
					
			array_push($this->mMagazines, new Magazine($this->mCore, $id));
			$Magazine = end($this->mMagazines);
			//Reset array
			reset($this->mMagazines);
			
			mkdir($Magazine->getFilePath(), 0777, true);
			
			$_SESSION['magazineId'] = $id;
			
			
			MessageManager::displayMessage('You created a magazine',1);	
		}
		public function editMagazine()
		{
			$data = $this->unserializePOST($_POST['data']);
			$issue = $data['issue'];
			$year = $data['year'];
			$id = $data['magazineId'];
			
			$sql = "UPDATE magazines SET issue = :issue, year = :year WHERE id = :id";  
			$stmt = $this->mDatabase->prepare($sql);
			$stmt->execute(array(':issue'=>$issue,
				':year'=>$year,
				':id'=>$id
				));
			
			MessageManager::displayMessage('You edited the magazine.',1);	
		}
		public function displayDeleteMagazine()
		{
			
			$id = $_POST['magazineId'];
			$Magazine = $this->mMagazines[$this->getMagazineIndex($id)];
			echo '<h4>Are you sure you want to delete '.$Magazine->getName().'? </h4>';
			echo $Magazine->getFrontCover();
			echo '<input type="hidden" id="action" value="DeleteMagazine" />';
		}
		public function deleteMagazine()
		{
			$id = $_POST['magazineId'];
			$this->mMagazines[$this->getMagazineIndex($id)]->delete();
			MessageManager::displayMessage('You deleted the magazine.',1);
		}
		public function reorderSections($sections)
		{	
			$i = 0;
			foreach ($sections as $section)
			{
				$sql = "UPDATE sections SET sectionOrder = :sectionOrder WHERE id = :id";  
				$stmt = $this->mDatabase->prepare($sql);
				$stmt->execute(array(':sectionOrder'=>$i, ':id'=>$section));
				$i++;
			}
			$this->displayEditor(1);
		}
		public function unserializePOST($array)
		{
			$values = array();
			$i = 0;
			foreach ($array as $value)
			{
				$name = $value['name'];
				$values[$name] = $value['value'];
			}
			return $values;
		}
		public function getId()
		{
			return $this->mId;	
		}
		public function getName()
		{
			return $this->mName;	
		}
		public function getLogo()
		{
			return $this->mLogo;	
		}
		public function getMagazines()
		{
			return $this->mMagazines;	
		}
		public function getMagazine($id)
		{
			if (count($this->mMagazines) > 0)
			{
				foreach ($this->mMagazines as $Magazine)
				{
					if ($Magazine->getId() == $id)
						return $Magazine;	
				}
			}
		}
		public function getMagazineIndex($id)
		{
			$i = 0;
			foreach ($this->mMagazines as $Magazine)
			{
				if ($Magazine->getId() == $id)
					return $i;
				$i++;
			}
			return NULL;	
		}
		public function getWebsiteLink()
		{
			return '<a href="'.$this->mURL.'" target="_blank">'.$this->removeHTTP().'</a>';
		}
		public function removeHTTP() {
			
			$url = ltrim($this->mURL, "http://");
			return rtrim($url, "/");
		}
		public function getMagazineId()
		{
			if (!empty($_GET['magazineId']))
				return $_GET['magazineId'];
			else if (!empty($_POST['magazineId']))
				return $_POST['magazineId'];
			else if (!empty($_SESSION['magazineId']))
				return $_SESSION['magazineId'];
			return false;
		}
		public function getPublicationId()
		{
			if (!empty($_GET['publicationId']))
				return $_GET['publicationId'];
			else if (!empty($_POST['publicationId']))
				return $_POST['publicationId'];
			else if (!empty($_SESSION['publicationId']))
				return $_SESSION['publicationId'];
			return false;
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