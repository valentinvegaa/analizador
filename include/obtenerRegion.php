<?php
/**
 * Created by PhpStorm.
 * User: valentinvegaa
 * Date: 11-02-2015
 * Time: 15:36
 */

function obtenerRegion($c){
    $regiones=array(
        'Arica y Parinacota'=>array(
            'Arica'=>array("Arica","Camarones"),
            'Parinacota'=>array("Putre","General Lagos")
        ),
        'Tarapacá'=>array(
            'Iquique'=>array("Iquique","Alto Hospicio"),
            'Tamarugal'=>array("Pozo Almonte","Camiña","Colchane","Huara,Pica"),
        ),
        'Antofagasta'=>array(
            'Antofagasta'=>array("Antofagasta","Mejillones","Sierra Gorda","Taltal"),
            'El Loa'=>array("Calama","Ollagüe","San Pedro de Atacama"),
            'Tocopilla'=>array("Tocopilla","María Elena"),
        ),
        'Atacama'=>array(
            'Copiapó'=>array("Copiapó","Caldera","Tierra Amarilla"),
            'Chañaral'=>array("Chañaral","Diego de Almagro"),
            'Huasco'=>array("Vallenar","Alto del Carmen","Freirina","Huasco"),
        ),
        'Coquimbo'=>array(
            'Elqui'=>array("La Serena","Coquimbo","Andacollo","La Higuera","Paiguano","Vicuña"),
            'Choapa'=>array("Illapel","Canela","Los Vilos","Salamanca"),
            'Limarí'=>array("Ovalle","Combarbalá","Monte Patria","Punitaqui","Río Hurtado"),
        ),
        'Valparaíso'=>array(
            'Valparaíso'=>array("Valparaíso","Casablanca","Concón","Juan Fernández","Puchuncaví","Quintero","Viña del Mar"),
            'Isla de Pascua'=>array("Isla de Pascua"),
            'Los Andes'=>array("Los Andes","Calle Larga","Rinconada","San Esteban"),
            'Petorca'=>array("La Ligua","Cabildo","Papudo","Petorca","Zapallar"),
            'Quillota'=>array("Quillota","Calera","Hijuelas","La Cruz","Nogales"),
            'San Antonio'=>array("San Antonio","Algarrobo","Cartagena","El Quisco","El Tabo","Santo Domingo"),
            'San Felipe de Aconcagua'=>array("San Felipe","Catemu","Llaillay","Panquehue","Putaendo","Santa María"),
            'Marga Marga'=>array("Quilpué","Limache","Olmué","Villa Alemana"),
        ),
        'Región del Libertador Gral. Bernardo O’Higgins'=>array(
            'Cachapoal'=>array("Rancagua","Codegua","Coinco","Coltauco","Doñihue","Graneros","Las Cabras","Machalí","Malloa","Mostazal","Olivar","Peumo","Pichidegua","Quinta de Tilcoco","Rengo","Requínoa","San Vicente"),
            'Cardenal Caro'=>array("Pichilemu","La Estrella","Litueche","Marchihue","Navidad","Paredones"),
            'Colchagua'=>array("San Fernando","Chépica","Chimbarongo","Lolol","Nancagua","Palmilla","Peralillo","Placilla","Pumanque","Santa Cruz"),
        ),
        'Región del Maule'=>array(
            'Talca'=>array("Talca","Constitución","Curepto","Empedrado","Maule","Pelarco","Pencahue","Río Claro","San Clemente","San Rafael"),
            'Cauquenes'=>array("Cauquenes","Chanco","Pelluhue"),
            'Curicó'=>array("Curicó","Hualañé","Licantén","Molina","Rauco","Romeral","Sagrada Familia","Teno","Vichuquén"),
            'Linares'=>array("Linares","Colbún","Longaví","Parral","Retiro","San Javier","Villa Alegre","Yerbas Buenas"),
        ),
        'Región del Biobío'=>array(
            'Concepción'=>array("Concepción","Coronel","Chiguayante","Florida","Hualqui","Lota","Penco","San Pedro de la Paz","Santa Juana","Talcahuano","Tomé","Hualpén"),
            'Arauco'=>array("Lebu","Arauco","Cañete","Contulmo","Curanilahue","Los Álamos","Tirúa"),
            'Biobío'=>array("Los Ángeles","Antuco","Cabrero","Laja","Mulchén","Nacimiento","Negrete","Quilaco","Quilleco","San Rosendo","Santa Bárbara","Tucapel","Yumbel","Alto Biobío"),
            'Ñuble'=>array("Chillán","Bulnes","Cobquecura","Coelemu","Coihueco","Chillán Viejo","El armen","Ninhue","Ñiquén","Pemuco","Pinto","Portezuelo","Quillón","Quirihue","Ránquil","San Carlos","San Fabián","San Ignacio","San Nicolás","Treguaco","Yungay"),
        ),
        'Región de la Araucanía'=>array(
            'Cautín'=>array("Temuco","Carahue","Cunco","Curarrehue","Freire","Galvarino","Gorbea","Lautaro","Loncoche","Melipeuco","Nueva Imperial","Padre las Casas","Perquenco","Pitrufquén","Pucón","Saavedra","Teodoro Schmidt","Toltén","Vilcún","Villarrica","Cholchol"),
            'Malleco'=>array("Angol","Collipulli","Curacautín","Ercilla","Lonquimay","Los Sauces","Lumaco","Purén","Renaico","Traiguén","Victoria"),
        ),
        'Región de los Ríos'=>array(
            'Valdivia'=>array("Valdivia","Corral","Lanco","Los Lagos","Máfil","Mariquina","Paillaco","Panguipulli"),
            'Ranco'=>array("La Unión","Futrono","Lago Ranco","Río Bueno"),
        ),
        'Región de los Lagos'=>array(
            'Llanquihue'=>array("Puerto Montt","Calbuco","Cochamó","Fresia","Frutillar","Los Muermos","Llanquihue","Maullín","Puerto Varas"),
            'Chiloé'=>array("Castro","Ancud","Chonchi","Curaco de Vélez","Dalcahue","Puqueldón","Queilén","Quellón","Quemchi","Quinchao"),
            'Osorno'=>array("Osorno","Puerto Octay","Purranque","Puyehue","Río Negro","San Juan de la Costa","San Pablo"),
            'Palena'=>array("Chaitén","Futaleufú","Hualaihué","Palena"),
        ),
        'Región Aisén del Gral. Carlos Ibáñez del Campo'=>array(
            'Coihaique'=>array("Coihaique","Lago Verde"),
            'Aisén'=>array("Aisén","Cisnes","Guaitecas"),
            'Capitán Prat'=>array("Cochrane","O’Higgins","Tortel"),
            'General Carrera'=>array("Chile Chico","Río Ibáñez"),
        ),
        'Región de Magallanes y de la Antártica Chilena'=>array(
            'Magallanes'=>array("Punta Arenas","Laguna Blanca","Río Verde","San Gregorio"),
            'Antártica Chilena'=>array("Cabo de Hornos (Ex Navarino)","Antártica"),
            'Tierra del Fuego'=>array("Porvenir","Primavera","Timaukel"),
            'Última Esperanza'=>array("Natales","Torres del Paine"),
        ),
        'Región Metropolitana de Santiago'=>array(
            'Santiago'=>array("Santiago","Cerrillos","Cerro Navia","Conchalí","El Bosque","Estación Central","Huechuraba","Independencia","La Cisterna","La Florida","La Granja","La Pintana","La Reina","Las Condes","Lo Barnechea","Lo Espejo","Lo Prado","Macul","Maipú","Ñuñoa","Pedro Aguirre Cerda","Peñalolén","Providencia","Pudahuel","Quilicura","Quinta Normal","Recoleta","Renca","San Joaquín","San Miguel","San Ramón","Vitacura"),
            'Cordillera'=>array("Puente Alto","Pirque","San José de Maipo"),
            'Chacabuco'=>array("Colina","Lampa","Tiltil"),
            'Maipo'=>array("San Bernardo","Buin","Calera de Tango","Paine"),
            'Melipilla'=>array("Melipilla","Alhué","Curacaví","María Pinto","San Pedro"),
            'Talagante'=>array("Talagante","El Monte","Isla de Maipo","Padre Hurtado","Peñaflor")
        )
    );
    $out='';
    foreach($regiones as $region=>$provincias){
        foreach($provincias as $provincia=>$comunas){
            foreach($comunas as $comuna){
                if(strcmp(sanear($c),sanear($comuna))==0){
                    $out = $region;
                }
            }
        }
    }
    return strlen($out)==0?'N/A':$out;
}
function sanear($s) {
    $s = str_replace(
        array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
        array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
        $s
    );

    $s = str_replace(
        array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
        array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
        $s
    );

    $s = str_replace(
        array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
        array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
        $s
    );

    $s = str_replace(
        array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
        array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
        $s
    );

    $s = str_replace(
        array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
        array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
        $s
    );

    $s = str_replace(
        array('ñ', 'Ñ', 'ç', 'Ç'),
        array('n', 'N', 'c', 'C',),
        $s
    );
    $s = str_replace(
        array(" ","\\", "¨", "º", "-", "~",
            "#", "@", "|", "!", "\"",
            "·", "$", "%", "&", "/",
            "(", ")", "?", "'", "¡",
            "¿", "[", "^", "`", "]",
            "+", "}", "{", "¨", "´",
            ">", "<", ";", ",", ":",
            ".", " "),
        '',
        $s
    );
    return strtolower($s);
}
?>