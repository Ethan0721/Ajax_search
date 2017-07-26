 <?php
 // Author: Jian Zhao
 
	class TitleDataBase {
		private $DB;
		
		// table quote. In this assignment you will also need a new table named 'users'
		public function __construct() {
			$db = 'mysql:dbname=imdb;charset=utf8;host=127.0.0.1';
			$user = 'root';
			$password = '';
			
			try {
				$this->DB = new PDO ( $db, $user, $password );
				$this->DB->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			} catch ( PDOException $e ) {
				echo ('Error establishing Connection');
				exit ();
			}
		} // encodeuri()

		public function get_both($title, $name) {
			$pos = strpos ( $name, ',' );
			if ($pos !== false) {
				$lastName = substr ( $name, 0, $pos );
				$firstName = substr ( $name, $pos + 1 );
				$firstName = $firstName . "%";
				$lastName = $lastName . "%";
				$firstName=ltrim($firstName);
				$lastName=ltrim($lastName);	
				$title = $title . "%";
				$stmt = $this->DB->prepare ( "SELECT movies.name as movieTitle , actors.first_name as fName , actors.last_name as lName , movies.year as movieYear
											FROM actors
											JOIN roles on actors.id = roles.actor_id
											JOIN movies on roles.movie_id =movies.id
											WHERE actors.first_name like :firstName AND actors.last_name LIKE :lastName AND  movies.name LIKE :title
											limit 60 ;" );
				$stmt->bindParam ( 'lastName', $lastName );
				$stmt->bindParam ( 'firstName', $firstName );
			} else {
				$lastName = $name;
				$title = $title . "%";
				$lastName = $lastName . "%";
				$lastName=ltrim($lastName);
				$stmt = $this->DB->prepare ( "SELECT movies.name as movieTitle, actors.first_name as fName, actors.last_name as lName,movies.year as movieYear
											FROM actors
											JOIN roles on actors.id = roles.actor_id
											JOIN movies on roles.movie_id = movies.id
											WHERE actors.last_name LIKE :lastName AND movies.name LIKE :title 
											limit 60;" );
				$stmt->bindParam ( 'lastName', $lastName );
			}
			$stmt->bindParam ( 'title', $title );
			$stmt->execute ();
			$arr = $stmt->fetchAll ( PDO::FETCH_ASSOC );
			$length = count ( $arr );
			$str = '';
			for($x = 0; $x < $length; $x ++) {
				$str = $str . "<tr><td>" . $arr [$x] ['movieTitle'] . " </td><td>" . $arr [$x] ['fName'] . " " . $arr [$x] ['lName'] . " </td><td>" . $arr [$x] ['movieYear'] . "</td></tr>";
			}
			return $str;
		}
	} // end class DatabaseAdaptor
	
	$movieTitles = new TitleDataBase ();
	?>