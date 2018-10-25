<?php
/**
 * Application form definiton.
 *
 * @file
 * @author Niklas Laxström
 * @license GPL-2.0+
 */

namespace Ainut;

class ApplicationForm {
	public function getFormFields( $defaults, $msg ) {
		$fields = [];

		$fields['title'] = [
			'type' => 'text',
			'label-message' => 'ainut-app-name',
			'maxlength' => 150,
			'default' => isset( $defaults['title'] ) ? $defaults['title'] : '',
			'required' => true,
		];

		$fields['categories'] = [
			'type' => 'multiselect',
			'flatlist' => true,
			'label-message' => 'ainut-app-categories',
			'help-message' => 'ainut-app-categories-notice',
			'options-messages' => [
				'akp-s-juhlat' => 'akp-s-juhlat',
				'akp-s-musiikki' => 'akp-s-musiikki',
				'akp-s-esittävät' => 'akp-s-esittävät',
				'akp-s-suullinen' => 'akp-s-suullinen',
				'akp-s-käsityö' => 'akp-s-käsityö',
				'akp-s-ruoka' => 'akp-s-ruoka',
				'akp-s-pelit' => 'akp-s-pelit',
				'akp-s-luonto' => 'akp-s-luonto',
			],
			'default' => isset( $defaults['categories'] ) ? $defaults['categories'] : [],
			'validation-callback' => function ( $a ) use ( $msg ) {
				return $a !== [] ? true : $msg( 'ainut-app-err-cat1' );
			},
		];

		$fields['location'] = [
			'class' => HTMLTagsField::class,
			'label-message' => 'ainut-app-location',
			'help-message' => 'ainut-app-location-notice',
			'options' => self::$locations,
			'default' => isset( $defaults['location'] ) ? $defaults['location'] : [],
			'multiple' => true,
			'validation-callback' => function ( $a ) use ( $msg ) {
				return $a !== [] ? true : $msg( 'ainut-app-err-loc1' );
			},
		];

		$texts = [
			'people' => 'mw-ainut-len-4000',
			'present' => 'mw-ainut-len-5000',
			'past' => 'mw-ainut-len-5000',
			'relay' => 'mw-ainut-len-5000',
			'protection' => 'mw-ainut-len-4000',
			'recording' => 'mw-ainut-len-4000',
			'future' => 'mw-ainut-len-5000',
			'orgs' => null,
			'links' => null,
		];
		foreach ( $texts as $name => $len ) {
			$fields[$name] = [
				'type' => 'textarea',
				'label-message' => "ainut-app-$name",
				'help-message' => "ainut-app-$name-notice",
				'rows' => 5,
				'default' => isset( $defaults[$name] ) ? $defaults[$name] : '',
				'cssclass' => $len,
			];
		}

		return $fields;
	}

	protected static $locations = [
		'Koko Suomi' => 'Suomi',
		'Maakunnat' => [
			'Ahvenanmaan maakunta' => 'Ahvenanmaan maakunta',
			'Etelä-Karjala' => 'Etelä-Karjala',
			'Etelä-Pohjanmaa' => 'Etelä-Pohjanmaa',
			'Etelä-Savo' => 'Etelä-Savo',
			'Kainuu' => 'Kainuu',
			'Kanta-Häme' => 'Kanta-Häme',
			'Keski-Pohjanmaa' => 'Keski-Pohjanmaa',
			'Keski-Suomi' => 'Keski-Suomi',
			'Kymenlaakso' => 'Kymenlaakso',
			'Lappi' => 'Lappi',
			'Pirkanmaa' => 'Pirkanmaa',
			'Pohjanmaa' => 'Pohjanmaa',
			'Pohjois-Karjala' => 'Pohjois-Karjala',
			'Pohjois-Pohjanmaa' => 'Pohjois-Pohjanmaa',
			'Pohjois-Savo' => 'Pohjois-Savo',
			'Päijät-Häme' => 'Päijät-Häme',
			'Satakunta' => 'Satakunta',
			'Uusimaa' => 'Uusimaa',
			'Varsinais-Suomi' => 'Varsinais-Suomi',
		],
		'Kunnat' => [
			'Akaa' => 'Akaa',
			'Alajärvi' => 'Alajärvi',
			'Alavieska' => 'Alavieska',
			'Alavus' => 'Alavus',
			'Asikkala' => 'Asikkala',
			'Askola' => 'Askola',
			'Aura' => 'Aura',
			'Brändö' => 'Brändö',
			'Eckerö' => 'Eckerö',
			'Enonkoski' => 'Enonkoski',
			'Enontekiö' => 'Enontekiö',
			'Espoo' => 'Espoo',
			'Eura' => 'Eura',
			'Eurajoki' => 'Eurajoki',
			'Evijärvi' => 'Evijärvi',
			'Finström' => 'Finström',
			'Forssa' => 'Forssa',
			'Föglö' => 'Föglö',
			'Geta' => 'Geta',
			'Haapajärvi' => 'Haapajärvi',
			'Haapavesi' => 'Haapavesi',
			'Hailuoto' => 'Hailuoto',
			'Halsua' => 'Halsua',
			'Hamina' => 'Hamina',
			'Hammarland' => 'Hammarland',
			'Hankasalmi' => 'Hankasalmi',
			'Hanko' => 'Hanko',
			'Harjavalta' => 'Harjavalta',
			'Hartola' => 'Hartola',
			'Hattula' => 'Hattula',
			'Hausjärvi' => 'Hausjärvi',
			'Heinola' => 'Heinola',
			'Heinävesi' => 'Heinävesi',
			'Helsinki' => 'Helsinki',
			'Hirvensalmi' => 'Hirvensalmi',
			'Hollola' => 'Hollola',
			'Honkajoki' => 'Honkajoki',
			'Huittinen' => 'Huittinen',
			'Humppila' => 'Humppila',
			'Hyrynsalmi' => 'Hyrynsalmi',
			'Hyvinkää' => 'Hyvinkää',
			'Hämeenkyrö' => 'Hämeenkyrö',
			'Hämeenlinna' => 'Hämeenlinna',
			'Ii' => 'Ii',
			'Iisalmi' => 'Iisalmi',
			'Iitti' => 'Iitti',
			'Ikaalinen' => 'Ikaalinen',
			'Ilmajoki' => 'Ilmajoki',
			'Ilomantsi' => 'Ilomantsi',
			'Imatra' => 'Imatra',
			'Inari' => 'Inari',
			'Inkoo' => 'Inkoo',
			'Isojoki' => 'Isojoki',
			'Isokyrö' => 'Isokyrö',
			'Janakkala' => 'Janakkala',
			'Joensuu' => 'Joensuu',
			'Jokioinen' => 'Jokioinen',
			'Jomala' => 'Jomala',
			'Joroinen' => 'Joroinen',
			'Joutsa' => 'Joutsa',
			'Juankoski' => 'Juankoski',
			'Juuka' => 'Juuka',
			'Juupajoki' => 'Juupajoki',
			'Juva' => 'Juva',
			'Jyväskylä' => 'Jyväskylä',
			'Jämijärvi' => 'Jämijärvi',
			'Jämsä' => 'Jämsä',
			'Järvenpää' => 'Järvenpää',
			'Kaarina' => 'Kaarina',
			'Kaavi' => 'Kaavi',
			'Kajaani' => 'Kajaani',
			'Kalajoki' => 'Kalajoki',
			'Kangasala' => 'Kangasala',
			'Kangasniemi' => 'Kangasniemi',
			'Kankaanpää' => 'Kankaanpää',
			'Kannonkoski' => 'Kannonkoski',
			'Kannus' => 'Kannus',
			'Karijoki' => 'Karijoki',
			'Karkkila' => 'Karkkila',
			'Karstula' => 'Karstula',
			'Karvia' => 'Karvia',
			'Kaskinen' => 'Kaskinen',
			'Kauhajoki' => 'Kauhajoki',
			'Kauhava' => 'Kauhava',
			'Kauniainen' => 'Kauniainen',
			'Kaustinen' => 'Kaustinen',
			'Keitele' => 'Keitele',
			'Kemi' => 'Kemi',
			'Kemijärvi' => 'Kemijärvi',
			'Keminmaa' => 'Keminmaa',
			'Kemiönsaari' => 'Kemiönsaari',
			'Kempele' => 'Kempele',
			'Kerava' => 'Kerava',
			'Keuruu' => 'Keuruu',
			'Kihniö' => 'Kihniö',
			'Kinnula' => 'Kinnula',
			'Kirkkonummi' => 'Kirkkonummi',
			'Kitee' => 'Kitee',
			'Kittilä' => 'Kittilä',
			'Kiuruvesi' => 'Kiuruvesi',
			'Kivijärvi' => 'Kivijärvi',
			'Kokemäki' => 'Kokemäki',
			'Kokkola' => 'Kokkola',
			'Kolari' => 'Kolari',
			'Konnevesi' => 'Konnevesi',
			'Kontiolahti' => 'Kontiolahti',
			'Korsnäs' => 'Korsnäs',
			'Koski Tl' => 'Koski Tl',
			'Kotka' => 'Kotka',
			'Kouvola' => 'Kouvola',
			'Kristiinankaupunki' => 'Kristiinankaupunki',
			'Kruunupyy' => 'Kruunupyy',
			'Kuhmo' => 'Kuhmo',
			'Kuhmoinen' => 'Kuhmoinen',
			'Kumlinge' => 'Kumlinge',
			'Kuopio' => 'Kuopio',
			'Kuortane' => 'Kuortane',
			'Kurikka' => 'Kurikka',
			'Kustavi' => 'Kustavi',
			'Kuusamo' => 'Kuusamo',
			'Kyyjärvi' => 'Kyyjärvi',
			'Kärkölä' => 'Kärkölä',
			'Kärsämäki' => 'Kärsämäki',
			'Kökar' => 'Kökar',
			'Lahti' => 'Lahti',
			'Laihia' => 'Laihia',
			'Laitila' => 'Laitila',
			'Lapinjärvi' => 'Lapinjärvi',
			'Lapinlahti' => 'Lapinlahti',
			'Lappajärvi' => 'Lappajärvi',
			'Lappeenranta' => 'Lappeenranta',
			'Lapua' => 'Lapua',
			'Laukaa' => 'Laukaa',
			'Lemi' => 'Lemi',
			'Lemland' => 'Lemland',
			'Lempäälä' => 'Lempäälä',
			'Leppävirta' => 'Leppävirta',
			'Lestijärvi' => 'Lestijärvi',
			'Lieksa' => 'Lieksa',
			'Lieto' => 'Lieto',
			'Liminka' => 'Liminka',
			'Liperi' => 'Liperi',
			'Lohja' => 'Lohja',
			'Loimaa' => 'Loimaa',
			'Loppi' => 'Loppi',
			'Loviisa' => 'Loviisa',
			'Luhanka' => 'Luhanka',
			'Lumijoki' => 'Lumijoki',
			'Lumparland' => 'Lumparland',
			'Luoto' => 'Luoto',
			'Luumäki' => 'Luumäki',
			'Luvia' => 'Luvia',
			'Maalahti' => 'Maalahti',
			'Maarianhamina' => 'Maarianhamina',
			'Marttila' => 'Marttila',
			'Masku' => 'Masku',
			'Merijärvi' => 'Merijärvi',
			'Merikarvia' => 'Merikarvia',
			'Miehikkälä' => 'Miehikkälä',
			'Mikkeli' => 'Mikkeli',
			'Muhos' => 'Muhos',
			'Multia' => 'Multia',
			'Muonio' => 'Muonio',
			'Mustasaari' => 'Mustasaari',
			'Muurame' => 'Muurame',
			'Mynämäki' => 'Mynämäki',
			'Myrskylä' => 'Myrskylä',
			'Mäntsälä' => 'Mäntsälä',
			'Mänttä-Vilppula' => 'Mänttä-Vilppula',
			'Mäntyharju' => 'Mäntyharju',
			'Naantali' => 'Naantali',
			'Nakkila' => 'Nakkila',
			'Nivala' => 'Nivala',
			'Nokia' => 'Nokia',
			'Nousiainen' => 'Nousiainen',
			'Nurmes' => 'Nurmes',
			'Nurmijärvi' => 'Nurmijärvi',
			'Närpiö' => 'Närpiö',
			'Orimattila' => 'Orimattila',
			'Oripää' => 'Oripää',
			'Orivesi' => 'Orivesi',
			'Oulainen' => 'Oulainen',
			'Oulu' => 'Oulu',
			'Outokumpu' => 'Outokumpu',
			'Padasjoki' => 'Padasjoki',
			'Paimio' => 'Paimio',
			'Paltamo' => 'Paltamo',
			'Parainen' => 'Parainen',
			'Parikkala' => 'Parikkala',
			'Parkano' => 'Parkano',
			'Pedersören kunta' => 'Pedersören kunta',
			'Pelkosenniemi' => 'Pelkosenniemi',
			'Pello' => 'Pello',
			'Perho' => 'Perho',
			'Pertunmaa' => 'Pertunmaa',
			'Petäjävesi' => 'Petäjävesi',
			'Pieksämäki' => 'Pieksämäki',
			'Pielavesi' => 'Pielavesi',
			'Pietarsaari' => 'Pietarsaari',
			'Pihtipudas' => 'Pihtipudas',
			'Pirkkala' => 'Pirkkala',
			'Polvijärvi' => 'Polvijärvi',
			'Pomarkku' => 'Pomarkku',
			'Pori' => 'Pori',
			'Pornainen' => 'Pornainen',
			'Porvoo' => 'Porvoo',
			'Posio' => 'Posio',
			'Pudasjärvi' => 'Pudasjärvi',
			'Pukkila' => 'Pukkila',
			'Punkalaidun' => 'Punkalaidun',
			'Puolanka' => 'Puolanka',
			'Puumala' => 'Puumala',
			'Pyhtää' => 'Pyhtää',
			'Pyhäjoki' => 'Pyhäjoki',
			'Pyhäjärvi' => 'Pyhäjärvi',
			'Pyhäntä' => 'Pyhäntä',
			'Pyhäranta' => 'Pyhäranta',
			'Pälkäne' => 'Pälkäne',
			'Pöytyä' => 'Pöytyä',
			'Raahe' => 'Raahe',
			'Raasepori' => 'Raasepori',
			'Raisio' => 'Raisio',
			'Rantasalmi' => 'Rantasalmi',
			'Ranua' => 'Ranua',
			'Rauma' => 'Rauma',
			'Rautalampi' => 'Rautalampi',
			'Rautavaara' => 'Rautavaara',
			'Rautjärvi' => 'Rautjärvi',
			'Reisjärvi' => 'Reisjärvi',
			'Riihimäki' => 'Riihimäki',
			'Ristijärvi' => 'Ristijärvi',
			'Rovaniemi' => 'Rovaniemi',
			'Ruokolahti' => 'Ruokolahti',
			'Ruovesi' => 'Ruovesi',
			'Rusko' => 'Rusko',
			'Rääkkylä' => 'Rääkkylä',
			'Saarijärvi' => 'Saarijärvi',
			'Salla' => 'Salla',
			'Salo' => 'Salo',
			'Saltvik' => 'Saltvik',
			'Sastamala' => 'Sastamala',
			'Sauvo' => 'Sauvo',
			'Savitaipale' => 'Savitaipale',
			'Savonlinna' => 'Savonlinna',
			'Savukoski' => 'Savukoski',
			'Seinäjoki' => 'Seinäjoki',
			'Sievi' => 'Sievi',
			'Siikainen' => 'Siikainen',
			'Siikajoki' => 'Siikajoki',
			'Siikalatva' => 'Siikalatva',
			'Siilinjärvi' => 'Siilinjärvi',
			'Simo' => 'Simo',
			'Sipoo' => 'Sipoo',
			'Siuntio' => 'Siuntio',
			'Sodankylä' => 'Sodankylä',
			'Soini' => 'Soini',
			'Somero' => 'Somero',
			'Sonkajärvi' => 'Sonkajärvi',
			'Sotkamo' => 'Sotkamo',
			'Sottunga' => 'Sottunga',
			'Sulkava' => 'Sulkava',
			'Sund' => 'Sund',
			'Suomussalmi' => 'Suomussalmi',
			'Suonenjoki' => 'Suonenjoki',
			'Sysmä' => 'Sysmä',
			'Säkylä' => 'Säkylä',
			'Taipalsaari' => 'Taipalsaari',
			'Taivalkoski' => 'Taivalkoski',
			'Taivassalo' => 'Taivassalo',
			'Tammela' => 'Tammela',
			'Tampere' => 'Tampere',
			'Tervo' => 'Tervo',
			'Tervola' => 'Tervola',
			'Teuva' => 'Teuva',
			'Tohmajärvi' => 'Tohmajärvi',
			'Toholampi' => 'Toholampi',
			'Toivakka' => 'Toivakka',
			'Tornio' => 'Tornio',
			'Turku' => 'Turku',
			'Tuusniemi' => 'Tuusniemi',
			'Tuusula' => 'Tuusula',
			'Tyrnävä' => 'Tyrnävä',
			'Ulvila' => 'Ulvila',
			'Urjala' => 'Urjala',
			'Utajärvi' => 'Utajärvi',
			'Utsjoki' => 'Utsjoki',
			'Uurainen' => 'Uurainen',
			'Uusikaarlepyy' => 'Uusikaarlepyy',
			'Uusikaupunki' => 'Uusikaupunki',
			'Vaala' => 'Vaala',
			'Vaasa' => 'Vaasa',
			'Valkeakoski' => 'Valkeakoski',
			'Valtimo' => 'Valtimo',
			'Vantaa' => 'Vantaa',
			'Varkaus' => 'Varkaus',
			'Vehmaa' => 'Vehmaa',
			'Vesanto' => 'Vesanto',
			'Vesilahti' => 'Vesilahti',
			'Veteli' => 'Veteli',
			'Vieremä' => 'Vieremä',
			'Vihti' => 'Vihti',
			'Viitasaari' => 'Viitasaari',
			'Vimpeli' => 'Vimpeli',
			'Virolahti' => 'Virolahti',
			'Virrat' => 'Virrat',
			'Vårdö' => 'Vårdö',
			'Vöyri' => 'Vöyri',
			'Ylitornio' => 'Ylitornio',
			'Ylivieska' => 'Ylivieska',
			'Ylöjärvi' => 'Ylöjärvi',
			'Ypäjä' => 'Ypäjä',
			'Ähtäri' => 'Ähtäri',
			'Äänekoski' => 'Äänekoski',
		]
	];
}
