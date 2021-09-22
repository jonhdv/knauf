<?php

declare(strict_types=1);

namespace App\Entity;

use InvalidArgumentException;

class CityType
{
    public const ALBACETE = 'albacete';
    public const ALICANTE = 'alicante';
    public const ALMERIA = 'almeria';
    public const ALAVA = 'alava';
    public const ASTURIAS = 'asturias';
    public const AVILA = 'avila';
    public const BADAJOZ = 'badajoz';
    public const BALEARES = 'baleares';
    public const BARCELONA = 'barcelona';
    public const BIZKAIA = 'bizkaia';
    public const BURGOS = 'burgos';
    public const CACERES = 'caceres';
    public const CADIZ = 'cadiz';
    public const CANTABRIA = 'cantabria';
    public const CASTELLON = 'castellon';
    public const CIUDAD_REAL = 'ciudad_real';
    public const CORDOBA = 'cordoba';
    public const A_CORUNA = 'a_coruna';
    public const CUENCA = 'cuenca';
    public const GIPUZKOA = 'gipuzkoa';
    public const GIRONA = 'girona';
    public const GRANADA = 'granada';
    public const GUADALAJARA = 'guadalajara';
    public const HUELVA = 'huelva';
    public const HUESCA = 'huesca';
    public const JAEN = 'jaen';
    public const LEON = 'leon';
    public const LLEIDA = 'lleida';
    public const LUGO = 'lugo';
    public const MADRID = 'madrid';
    public const MALAGA = 'malaga';
    public const MURCIA = 'murcia';
    public const NAVARRA = 'navarra';
    public const OURENSE = 'ourense';
    public const PALENCIA = 'palencia';
    public const LAS_PALMAS = 'las_palmas';
    public const PONTEVEDRA = 'pontevedra';
    public const LA_RIOJA = 'la_rioja';
    public const SALAMANCA = 'salamanca';
    public const SANTA_CRUZ_DE_TENERIFE = 'santa_cruz_de_tenerife';
    public const SEGOVIA = 'segovia';
    public const SEVILLA = 'sevilla';
    public const SORIA = 'soria';
    public const TARRAGONA = 'tarragona';
    public const TERUEL = 'teruel';
    public const TOLEDO = 'toledo';
    public const VALENCIA = 'valencia';
    public const VALLADOLID = 'valladolid';
    public const ZAMORA = 'zamora';
    public const ZARAGOZA = 'zaragoza';
    public const CEUTA = 'ceuta';
    public const MELILLA = 'melilla';
    public const SANTAREM = 'santarem';
    public const PORTOALEGRE = 'portoalegre';
    public const BEJA = 'beja';
    public const LISBOA = 'lisboa';
    public const FARO = 'faro';
    public const SETUBAL = 'setubal';
    public const EVORA = 'evora';
    public const FUNCHAL = 'funchal';
    public const LEIRIA = 'leiria';
    public const SAO_MIGUEL = 'sao_miguel';
    public const VIANA_DO_CASTELO = 'viana_do_castelo';
    public const BRAGA = 'braga';
    public const PORTO = 'porto';
    public const VILA_REAL = 'vila_real';
    public const BRAGANCA = 'braganca';
    public const AVEIRO = 'aveiro';
    public const VISEU = 'viseu';
    public const GUARDA = 'guarda';
    public const COIMBRA = 'coimbra';
    public const CASTELO_BRANCO = 'castelo_branco';
    public const MADEIRA = 'madeira';
    public const AZORES = 'azores';

    public static array $list = [
        self::ALBACETE => 'Albacete',
        self::ALICANTE => 'Alicante',
        self::ALMERIA => 'Almería',
        self::ALAVA => 'Álava',
        self::ASTURIAS => 'Asturias',
        self::AVILA => 'Ávila',
        self::BADAJOZ => 'Badajoz',
        self::BALEARES => 'Baleares',
        self::BARCELONA => 'Barcelona',
        self::BIZKAIA => 'Bizkaia',
        self::BURGOS => 'Burgos',
        self::CACERES => 'Cáceres',
        self::CADIZ => 'Cádiz',
        self::CANTABRIA => 'Cantabria',
        self::CASTELLON => 'Castellón',
        self::CIUDAD_REAL => 'Ciudad Real',
        self::CORDOBA => 'Córdoba',
        self::A_CORUNA => 'A Coruña',
        self::CUENCA => 'Cuenca',
        self::GIPUZKOA => 'Gipúzkoa',
        self::GIRONA => 'Girona',
        self::GRANADA => 'Granada',
        self::GUADALAJARA => 'Guadalajara',
        self::HUELVA => 'Huelva',
        self::HUESCA => 'Huesca',
        self::JAEN => 'Jaén',
        self::LEON => 'León',
        self::LLEIDA => 'Lleida',
        self::LUGO => 'Lugo',
        self::MADRID => 'Madrid',
        self::MALAGA => 'Málaga',
        self::MURCIA => 'Murcia',
        self::NAVARRA => 'Navarra',
        self::OURENSE => 'Ourense',
        self::PALENCIA => 'Palencia',
        self::LAS_PALMAS => 'Las Palmas',
        self::PONTEVEDRA => 'Pontevedra',
        self::LA_RIOJA => 'La Rioja',
        self::SALAMANCA => 'Salamanca',
        self::SANTA_CRUZ_DE_TENERIFE => 'Santa Cruz de Tenerife',
        self::SEGOVIA => 'Segovia',
        self::SEVILLA => 'Sevilla',
        self::SORIA => 'Soria',
        self::TARRAGONA => 'Tarragona',
        self::TERUEL => 'Teruel',
        self::TOLEDO => 'Toledo',
        self::VALENCIA => 'Valencia',
        self::VALLADOLID => 'Valladolid',
        self::ZAMORA => 'Zamora',
        self::ZARAGOZA => 'Zaragoza',
        self::CEUTA => 'Ceuta',
        self::MELILLA => 'Melilla',
        self::SANTAREM => 'Santarem',
        self::PORTOALEGRE => 'Portoalegre',
        self::BEJA => 'Beja',
        self::LISBOA => 'Lisboa',
        self::FARO => 'Faro',
        self::SETUBAL => 'Setúbal',
        self::EVORA => 'Evora',
        self::FUNCHAL => 'Funchal',
        self::LEIRIA => 'Leiria',
        self::SAO_MIGUEL => 'Sao Miguel',
        self::VIANA_DO_CASTELO => 'Viana do Castelo',
        self::BRAGA => 'Braga',
        self::PORTO => 'Porto',
        self::VILA_REAL => 'Vila Real',
        self::BRAGANCA => 'Bragança',
        self::AVEIRO => 'Aveiro',
        self::VISEU => 'Viseu',
        self::GUARDA => 'Guarda',
        self::COIMBRA => 'Coimbra',
        self::CASTELO_BRANCO => 'Castelo Branco',
        self::MADEIRA => 'Madeira',
        self::AZORES => 'Azores',
    ];

    private static array $types = [
        self::ALBACETE,
        self::ALICANTE,
        self::ALMERIA,
        self::ALAVA,
        self::ASTURIAS,
        self::AVILA,
        self::BADAJOZ,
        self::BALEARES,
        self::BARCELONA,
        self::BIZKAIA,
        self::BURGOS,
        self::CACERES,
        self::CADIZ,
        self::CANTABRIA,
        self::CASTELLON,
        self::CIUDAD_REAL,
        self::CORDOBA,
        self::A_CORUNA,
        self::CUENCA,
        self::GIPUZKOA,
        self::GIRONA,
        self::GRANADA,
        self::GUADALAJARA,
        self::HUELVA,
        self::HUESCA,
        self::JAEN,
        self::LEON,
        self::LLEIDA,
        self::LUGO,
        self::MADRID,
        self::MALAGA,
        self::MURCIA,
        self::NAVARRA,
        self::OURENSE,
        self::PALENCIA,
        self::LAS_PALMAS,
        self::PONTEVEDRA,
        self::LA_RIOJA,
        self::SALAMANCA,
        self::SANTA_CRUZ_DE_TENERIFE,
        self::SEGOVIA,
        self::SEVILLA,
        self::SORIA,
        self::TARRAGONA,
        self::TERUEL,
        self::TOLEDO,
        self::VALENCIA,
        self::VALLADOLID,
        self::ZAMORA,
        self::ZARAGOZA,
        self::CEUTA,
        self::MELILLA,
        self::SANTAREM,
        self::PORTOALEGRE,
        self::BEJA,
        self::LISBOA,
        self::FARO,
        self::SETUBAL,
        self::EVORA,
        self::FUNCHAL,
        self::LEIRIA,
        self::SAO_MIGUEL,
        self::VIANA_DO_CASTELO,
        self::BRAGA,
        self::PORTO,
        self::VILA_REAL,
        self::BRAGANCA,
        self::AVEIRO,
        self::VISEU,
        self::GUARDA,
        self::COIMBRA,
        self::CASTELO_BRANCO,
        self::MADEIRA,
        self::AZORES,
    ];

    private string $type;

    private function __construct(string $type)
    {
        $this->type = $type;
    }

    public function __toString()
    {
        return $this->type;
    }

    public function getLabel(): string
    {
        return self::$list[$this->type];
    }

    public static function create(string $type): self
    {
        if (!in_array($type, self::$types)) {
            throw new InvalidArgumentException('Argumento no válido');
        }

        return new self($type);
    }

    public function getType(): string
    {
        return $this->type;
    }
}
