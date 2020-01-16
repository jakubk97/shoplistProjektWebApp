<?php
$f = fopen("semafor", "w");
flock($f, LOCK_EX);

$rawdata = file_get_contents("php://input");
$daneJSON = json_decode($rawdata, true);
$ok = true;

if ($daneJSON == null) {
	$wynik = array('rez' => false, 'kod' => 1, 'bo' => 'zły format');
	$ok = false;
}


if ($ok) {
	if (isset($daneJSON['id'])) {
		if (isset($daneJSON['opcja'])) {
			$co = (int) round($daneJSON['opcja'] * 1);
			switch ($co) {
				case 0:
					$wynik = odczytajListe($daneJSON);
					break;
				case 1:
					$wynik = pobierzUzytkownika($daneJSON);
					break;
				case 2:
					$wynik = usunPlikListy($daneJSON);
					break;
				case 3:
					$wynik = zapiszListyUzytkownika($daneJSON);
					break;
				case 4:
					$wynik = pobierzSlownikProduktow($daneJSON);
					break;
				case 5:
					$wynik = zapiszProduktyNaListe($daneJSON);
					break;
				case 6:
					$wynik = zapiszListyProduktow($daneJSON);
					break;
				case 7:
					$wynik = usunProduktZListy($daneJSON);
					break;

				default:
					$wynik = array('rez' => false, 'kod' => 3, 'bo' => 'nieznane polecenie');
			}
		} else {
			$wynik = array('rez' => false, 'kod' => 1, 'bo' => 'brak polecenia');
		}
	} else {
		$wynik = array('rez' => false, 'kod' => 1, 'bo' => 'brak id');
	}
}


$wynikS = json_encode($wynik, JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE + JSON_UNESCAPED_SLASHES);
echo $wynikS;



flock($f, LOCK_UN);
fclose($f);

function zad0($daneJSON)
{
	if (isset($daneJSON['id'])) {
		$id = $daneJSON['id'];
		if (isset($daneJSON['wzor']) && isset($daneJSON['wybor']) && isset($daneJSON['kolor']) && isset($daneJSON['data'])) {
			if (file_exists("zapisplikow5583678dzgd786geg/$id")) {
				$plik = json_decode(file_get_contents("zapisplikow5583678dzgd786geg/$id"), true);
				if ($plik == null) $plik = array();
			} else {
				$plik = array();
			}

			$post_data = array('wzor' => $daneJSON['wzor'], 'wybor' => $daneJSON['wybor'], 'kolor' => $daneJSON['kolor'], 'data' => $daneJSON['data']);

			file_put_contents("zapisplikow5583678dzgd786geg/$id",
				json_encode($post_data, JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE + JSON_UNESCAPED_SLASHES)
			);
			return array('rez' => true, 'kod' => 101, 'bo' => 'ok');
		} else {
			return array('rez' => false, 'kod' => 5, 'bo' => 'Brak poprawnych danych!');
		}
	} else {
		return array('rez' => false, 'kod' => 2, 'bo' => 'brak id');
	}
}

function zapiszListyUzytkownika($daneJSON)
{
	$id=$daneJSON['id'];
	if(file_exists("ListyUzytkownikow/$id.txt"))
	{		
		$post_data = array('listy' => $daneJSON['lista']);
		file_put_contents("ListyUzytkownikow/$id.txt",json_encode($post_data, JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE + JSON_UNESCAPED_SLASHES));
		return array('rez' => true, 'kod' => 101, 'bo' => 'ok');
	}
	else
	{
		fopen("ListyUzytkownikow/$id.txt", "w");
		$post_data = array('listy' => $daneJSON['lista']);
		file_put_contents("ListyUzytkownikow/$id.txt",json_encode($post_data, JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE + JSON_UNESCAPED_SLASHES));
		return array('rez' => true, 'kod' => 101, 'bo' => 'ok');

	}
}

function zapiszListyProduktow($daneJSON)
{
	$id=$daneJSON['id'];
	if(file_exists("Produkty/$id.txt"))
	{		
		$post_data = array('lista' => $daneJSON['lista']);
		file_put_contents("Produkty/$id.txt",json_encode($post_data, JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE + JSON_UNESCAPED_SLASHES));
		return array('rez' => true, 'kod' => 101, 'bo' => 'ok');
	}
	else
	{
		return array('rez'=>false, 'kod'=>5, 'bo'=>'Brak danych dla podanego pliku');
	}
}


function zapiszProduktyNaListe($daneJSON)
{
	$id=$daneJSON['id'];
	if(file_exists("Listy/$id.txt"))
	{		
		$post_data = array('wlasciciele' => $daneJSON['wlasciciele'],'produkty' => $daneJSON['produkty']);
		file_put_contents("Listy/$id.txt",json_encode($post_data, JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE + JSON_UNESCAPED_SLASHES));
		return array('rez' => true, 'kod' => 101, 'bo' => 'ok');
	}
	else
	{
		fopen("Listy/$id.txt", "w");
		$post_data = array('wlasciciele' => $daneJSON['wlasciciele'],'produkty' => $daneJSON['produkty']);
		file_put_contents("Listy/$id.txt",json_encode($post_data, JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE + JSON_UNESCAPED_SLASHES));
		return array('rez' => true, 'kod' => 101, 'bo' => 'ok');

	}
}

function pobierzSlownikProduktow($daneJSON)
{
	$id=$daneJSON['id'];
	if(file_exists("Produkty/$id.txt"))
	{		
		$plik=json_decode(file_get_contents("Produkty/$id.txt"),true);
		return array($plik);
	}
	else
	{
		return array('rez'=>false, 'kod'=>5, 'bo'=>'Brak danych');
	}
}

function pobierzUzytkownika( $daneJSON )
{
	$id=$daneJSON['id'];
	if(file_exists("ListyUzytkownikow/$id.txt"))
	{		
		$plik=json_decode(file_get_contents("ListyUzytkownikow/$id.txt"),true);
		return array($plik);
	}
	else
	{
		fopen("ListyUzytkownikow/$id.txt", "w");
		$post_data = array('listy' => '');
		file_put_contents("ListyUzytkownikow/$id.txt",json_encode($post_data, JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE + JSON_UNESCAPED_SLASHES));
		$plik=json_decode(file_get_contents("ListyUzytkownikow/$id.txt"),true);
		return array($plik);
	}
}

function odczytajListe( $daneJSON )
{
	$id=$daneJSON['id'];
	if(file_exists("Listy/$id.txt"))
	{		
		$plik=json_decode(file_get_contents("Listy/$id.txt"),true);
		return array($plik);
	}
	else
	{
		return array('rez'=>false, 'kod'=>5, 'bo'=>'brak danych dla id tego');
	}
}

function odczytajPlik( $daneJSON )
{
	$id=$daneJSON['id'];
	if(file_exists("$id"))
	{		
		$plik=json_decode(file_get_contents("$id"),true);

		return array($plik);
	}
	else
	{
		return array('rez'=>false, 'kod'=>5, 'bo'=>'brak danych dla id tego');
	}
}


function usunPlikListy( $daneJSON )
{
	$id=$daneJSON['id'];
	if(file_exists("Listy/$id.txt"))
	{		
		unlink("Listy/$id.txt");
		return "Pomyślnie usunięto plik";
	}
	else
	{
		return array('rez'=>false, 'kod'=>5, 'bo'=>'brak danych dla id tego');
	}
}

function usunProduktZListy( $daneJSON )
{
	$id=$daneJSON['id'];
	if(file_exists("Listy/$id.txt"))
	{		
		$post_data = array('wlasciciele' => $daneJSON['wlasciciele'],'produkty' => $daneJSON['produkty']);
		file_put_contents("Listy/$id.txt",json_encode($post_data, JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE + JSON_UNESCAPED_SLASHES));
		return array('rez' => true, 'kod' => 101, 'bo' => 'ok');
	}
	else
	{
		fopen("Listy/$id.txt", "w");
		$post_data = array('wlasciciele' => $daneJSON['wlasciciele'],'produkty' => $daneJSON['produkty']);
		file_put_contents("Listy/$id.txt",json_encode($post_data, JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE + JSON_UNESCAPED_SLASHES));
		return array('rez' => true, 'kod' => 101, 'bo' => 'ok');

	}
}

