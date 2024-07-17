<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $usuarios = [
            [
                'id' => 1,
                'nombre'=>'José Luis Ávila Jiménez', 
                'email'=>'jlavila@uco.es', 
                //'rol'=>'admin',
            ],
            [
                'id' => 2,
                'nombre'=>'Javier Ruiz Jurado', 
                'email'=>'i02rujuj@uco.es', 
                'rol'=>'admin',
            ],
            [
                'id' => 3,
                'nombre'=>'Manuel Cañas Ramírez', 
                'email'=>'epsc.director@uco.es', 
                'image' => asset('img/miembrosGobierno/ManoloCanyasRamirez.jpg'),
            ],
            [
                'id' => 4,
                'nombre'=>'Luis Manuel Fernández de Ahumada', 
                'email'=>'epsc.secretaria@uco.es', 
                'image' => asset('img/miembrosGobierno/LuisManuelFernandezAhumada.jpg'),
            ],
            [
                'id' => 5,
                'nombre'=>'Antonio Araúzo Azofra', 
                'email'=>'arauzo@uco.es',
            ],
            [
                'id' => 6,
                'nombre'=>'Martín Calero Lara', 
                'email'=>'martin.calero@uco.es', 
            ],
            [
                'id' => 7,
                'nombre'=>'Eduardo Cañete Carmona', 
                'email'=>'ecanete@uco.es', 
            ],
            [
                'id' => 8,
                'nombre'=>'Ángel Carmona Poyato', 
                'email'=>'ma1capoa@uco.es', 
            ],
            [
                'id' => 9,
                'nombre'=>'Antonio José Cubero Atienza', 
                'email'=>'ajcubero@uco.es', 
            ],
            [
                'id' => 10,
                'nombre'=>'José María Flores Arias', 
                'email'=>'jmflores@uco.es', 
            ],
            [
                'id' => 11,
                'nombre'=>'María Luque Rodríguez', 
                'email'=>'in1lurom@uco.es', 
            ],
            [
                'id' => 12,
                'nombre'=>'Carlos Diego Moreno Moreno', 
                'email'=>'cdiego@uco.es', 
            ],
            [
                'id' => 13,
                'nombre'=>'Francisco Javier Quiles Latorre', 
                'email'=>'el1qulaf@uco.es', 
            ],
            [
                'id' => 14,
                'nombre'=>'Isabel Pilar Santiago Chiquero', 
                'email'=>'el1sachi@uco.es', 
            ],
            [
                'id' => 15,
                'nombre'=>'Marta María Varo Martínez', 
                'email'=>'fa2vamam@uco.es', 
            ],
            [
                'id' => 16,
                'nombre'=>'Amelia Zafra Gómez', 
                'email'=>'azafra@uco.es', 
            ],
            [
                'id' => 17,
                'nombre'=>'Matías Liñán Reyes', 
                'email'=>'matias@uco.es', 
            ],
            [
                'id' => 18,
                'nombre'=>'Rocío Ruiz Bustos', 
                'email'=>'rrbustos@uco.es', 
            ],
            [
                'id' => 19,
                'nombre'=>'Juan Carlos Gámez Granados', 
                'email'=>'jcgamez@uco.es', 
            ],
            [
                'id' => 20,
                'nombre'=>'Andrés Alejandro Gersnoviez Milla', 
                'email'=>'andresgm@uco.es', 
            ],
            [
                'id' => 21,
                'nombre'=>'Cristina Martínez Ruedas', 
                'email'=>'z42maruc@uco.es', 
            ],
            [
                'id' => 22,
                'nombre'=>'María del Carmen Peces Prieto', 
                'email'=>'z12peprm@uco.es', 
            ],
            [
                'id' => 23,
                'nombre'=>'Rosa María Relaño Luna', 
                'email'=>'calidad.epsc@uco.es', 
            ],
            [
                'id' => 24,
                'nombre'=>'María del Carmen Rincón Andújar', 
                'email'=>'up3rianm@uco.es', 
            ],
            [
                'id' => 25,
                'nombre'=>'Jesús Escribano Serena', 
                'email'=>'jesus.escribano@uco.es', 
            ],
            [
                'id' => 26,
                'nombre'=>'Javier Juan Blanco', 
                'email'=>'javier.blanco@uco.es', 
            ],
            [
                'id' => 27,
                'nombre'=>'Irene Mérida Córdoba', 
                'email'=>'irene.merida@uco.es', 
            ],
            [
                'id' => 28,
                'nombre'=>'José Miguel Ríder Ramos', 
                'email'=>'jode.rider@uco.es', 
            ],
            [
                'id' => 29,
                'nombre'=>'Ana Jiménez Sánchez', 
                'email'=>'ana.jimenez@uco.es', 
            ],
            [
                'id' => 30,
                'nombre'=>'Rosa María Luna Gómez', 
                'email'=>'rosa.luna@uco.es', 
            ],
            [
                'id' => 31,
                'nombre'=>'Antonio Venzala Serrano', 
                'email'=>'antonio.venzala@uco.es', 
            ],
            [
                'id' => 32,
                'nombre'=>'Alma L. Albujer Brotons', 
                'email'=>'aalbujer@uco.es', 
            ],
            [
                'id' => 33,
                'nombre'=>'Ana Belén Ariza Villaverde', 
                'email'=>'ana.ariza@uco.es', 
            ],
            [
                'id' => 34,
                'nombre'=>'Joost van Duijn', 
                'email'=>'me2vavaj@uco.es',
                'image' => asset('img/miembrosGobierno/JoostVanDuijn.jpg'), 
            ],
            [
                'id' => 35,
                'nombre'=>'Juan Garrido Jurado', 
                'email'=>'juan.garrido@uco.es', 
            ],
            [
                'id' => 36,
                'nombre'=>'Guillermo Guerrero Vacas', 
                'email'=>'guillermo.guerrero@uco.es', 
            ],
            [
                'id' => 37,
                'nombre'=>'Francisco Ramón Lara Raya', 
                'email'=>'el1laraf@uco.es', 
                'image' => asset('img/miembrosGobierno/FranciscoRamonLaraRaya.jpg'),
            ],
            [
                'id' => 38,
                'nombre'=>'Isabel María Moreno García', 
                'email'=>'isabel.moreno@uco.es', 
                'image' => asset('img/miembrosGobierno/IsabelMorenoGarcia.jpg'),
            ],
            [
                'id' => 39,
                'nombre'=>'Fernando Peci López', 
                'email'=>'fernando.peci@uco.es', 
            ],
            [
                'id' => 40,
                'nombre'=>'Fabiano Tavares Pinto', 
                'email'=>'manuel.cruz@uco.es', 
            ],
            [
                'id' => 41,
                'nombre'=>'María Amalia Trillo Holgado', 
                'email'=>'maru.trillo@uco.es', 
            ],
            [
                'id' => 42,
                'nombre'=>'José Miguel Martínez Valle', 
                'email'=>'jmvalle@uco.es', 
            ],
            [
                'id' => 43,
                'nombre'=>'Pedro M. Barrera Lara', 
                'email'=>'pedro.barrera@uco.es', 
            ],
            [
                'id' => 44,
                'nombre'=>'Carlos Castillo Rodríguez', 
                'email'=>'ccastillo@uco.es', 
            ],
            [
                'id' => 45,
                'nombre'=>'Tania María Caro Romero', 
                'email'=>'vicepresidente@consejo-eps.uco.es', 
            ],
            [
                'id' => 46,
                'nombre'=>'Marta López Marabotto', 
                'email'=>'marta.lopez@uco.es', 
            ],
            [
                'id' => 47,
                'nombre'=>'José Luis Sanz Becerro', 
                'email'=>'jose.sanz@uco.es', 
            ],
            [
                'id' => 48,
                'nombre'=>'Rafael Ángel Morales Ruiz', 
                'email'=>'rafael.morales@uco.es', 
            ],
            [
                'id' => 48,
                'nombre'=>'Joaquín Mateos Barroso', 
                'email'=>'joaquin.mateos@uco.es', 
            ],
            [
                'id' => 49,
                'nombre'=>'José Luis Olivares Olmedilla', 
                'email'=>'el1ololj@uco.es', 
            ],
            [
                'id' => 50,
                'nombre'=>'María Isabel López Martínez', 
                'email'=>'q12lomam@uco.es', 
            ],
            [
                'id' => 51,
                'nombre'=>'Miguel J. González Redondo', 
                'email'=>'el1gorem@uco.es', 
            ],
            [
                'id' => 52,
                'nombre'=>'Pablo Romero Carrillo', 
                'email'=>'p62rocap@uco.es', 
            ],
            [
                'id' => 53,
                'nombre'=>'Ana Freire Muñoz', 
                'email'=>'ana.freire@uco.es', 
            ],
            [
                'id' => 54,
                'nombre'=>'Carmen María Navas Arrebola', 
                'email'=>'carmen.navas@uco.es', 
            ],
            [
                'id' => 55,
                'nombre'=>'David Bullejos Martín', 
                'email'=>'bullejos@uco.es', 
            ],
            [
                'id' => 56,
                'nombre'=>'Rafael Real Calvo', 
                'email'=>'rafael.real@uco.es', 
            ],
            [
                'id' => 57,
                'nombre'=>'Francisco J. Bellido Outeiriño', 
                'email'=>'fjbellido@uco.es', 
            ],
            [
                'id' => 58,
                'nombre'=>'Esther Molero Romero', 
                'email'=>'esther.molero@uco.es', 
            ],
            [
                'id' => 59,
                'nombre'=>'Francisco Cruz Navas', 
                'email'=>'r92crnaf@uco.es', 
            ],
            [
                'id' => 60,
                'nombre'=>'Juan Higuera Mohedano', 
                'email'=>'juan.higuera@uco.es', 
            ],
            [
                'id' => 61,
                'nombre'=>'José Luis Gutiérrez Gómez', 
                'email'=>'jose.guitierrezf@uco.es', 
            ],
            [
                'id' => 62,
                'nombre'=>'Tomás Morales Leal', 
                'email'=>'el1molet@uco.es', 
            ],
            [
                'id' => 63,
                'nombre'=>'Susana Luna Cosano', 
                'email'=>'bv2lucos@uco.es', 
            ],
            [
                'id' => 64,
                'nombre'=>'Mario Ruz Ruiz', 
                'email'=>'mario.ruz@uco.es', 
            ],
            [
                'id' => 65,
                'nombre'=>'David Eduardo Leiva Candia', 
                'email'=>'david.leiva@uco.es', 
            ],
            [
                'id' => 66,
                'nombre'=>'Rafael Pérez Alcántara', 
                'email'=>'ir1pealr@uco.es', 
            ],
            [
                'id' => 67,
                'nombre'=>'Oscar Rodríguez Alabanda', 
                'email'=>'orodriguez@uco.es', 
            ],
            [
                'id' => 68,
                'nombre'=>'Rafael Muñoz Salinas', 
                'email'=>'rmsalinas@uco.es', 
            ],
            [
                'id' => 69,
                'nombre'=>'Aurora Gil de Castro', 
                'email'=>'agil@uco.es', 
            ],
            [
                'id' => 70,
                'nombre'=>'María Brox Jiménez', 
                'email'=>'mbrox@uco.es', 
            ],
            [
                'id' => 71,
                'nombre'=>'Francisca Daza Sánchez', 
                'email'=>'um1dasaf@uco.es', 
            ],
            [
                'id' => 72,
                'nombre'=>'Roberto Espejo Mohedano', 
                'email'=>'ma1esmor@uco.es', 
            ],
            [
                'id' => 73,
                'nombre'=>'Rosalía Villa Jiménez', 
                'email'=>'z52vijir@uco.es', 
            ],
            [
                'id' => 74,
                'nombre'=>'Cristóbal Romero Morales', 
                'email'=>'cromero@uco.es', 
            ],
            [
                'id' => 75,
                'nombre'=>'Jorge E. Jiménez Hornero', 
                'email'=>'jjimenez@uco.es', 
            ],
            [
                'id' => 76,
                'nombre'=>'Elena Sánchez López', 
                'email'=>'g02saloe@uco.es', 
            ],
            [
                'id' => 77,
                'nombre'=>'Ruben Sola Guirado', 
                'email'=>'ir2sogur@uco.es', 
            ],
            [
                'id' => 78,
                'nombre'=>'Araceli García Núñez', 
                'email'=>'qo2ganua@uco.es', 
            ],
            [
                'id' => 79,
                'nombre'=>'Francisco Sánchez Rodríguez', 
                'email'=>'francisco.sanchez@uco.es', 
            ],
            [
                'id' => 80,
                'nombre'=>'Mª del Carmen García Martínez', 
                'email'=>'fa1crfej@uco.es', 
            ],
            [
                'id' => 81,
                'nombre'=>'Magdalena Caballero Campos', 
                'email'=>'magdalena.caballero@uco.es', 
            ],
            [
                'id' => 82,
                'nombre'=>'Rafael Castro Triguero', 
                'email'=>'me1catrr@uco.es', 
            ],
            [
                'id' => 83,
                'nombre'=>'Mª Antonia Cejas Molina', 
                'email'=>'molina@uco.es', 
            ],
            [
                'id' => 84,
                'nombre'=>'Óscar Nocete López', 
                'email'=>'oscar.nocete@uco.es', 
            ],
            [
                'id' => 85,
                'nombre'=>'José Manuel Palomares Muñoz', 
                'email'=>'jmpalomares@uco.es', 
            ],
            [
                'id' => 86,
                'nombre'=>'Gerardo Pedrós Pérez', 
                'email'=>'fa1pepeg@uco.es', 
            ],
            [
                'id' => 87,
                'nombre'=>'Nicolás Fernández García', 
                'email'=>'ma1fegan@uco.es', 
            ],
            [
                'id' => 88,
                'nombre'=>'Juan Jesús Luna Rodríguez', 
                'email'=>'juan.luna@uco.es', 
            ],
            [
                'id' => 89,
                'nombre'=>'Víctor Pallarés López', 
                'email'=>'vpallares@uco.es', 
            ],
            [
                'id' => 90,
                'nombre'=>'Antonio Moreno Muñoz', 
                'email'=>'amoreno@uco.es', 
            ],
            [
                'id' => 91,
                'nombre'=>'Manuel A. Ortiz López', 
                'email'=>'el1orlom@uco.es', 
            ],
            [
                'id' => 92,
                'nombre'=>'Francisco J. Vázquez Serrano', 
                'email'=>'fvazquez@uco.es', 
            ],
            [
                'id' => 93,
                'nombre'=>'Mª Dolores Redel Macías', 
                'email'=>'mdredel@uco.es', 
            ],
            [
                'id' => 94,
                'nombre'=>'Arturo Chica Pérez', 
                'email'=>'afchica@uco.es', 
            ],
        ];

        foreach($usuarios as $user){
            $u = new User();
            $u->name = $user['nombre'];
            $u->email = $user['email'];
            $u->email_verified_at = now();
            $u->password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'; /* Contraseña: password */
            $u->remember_token = Str::random(10);
            isset($user['image']) ? $u->image = $user['image'] : null;
            $u->save();

            if(isset($user['rol'])){
                $u->assignRole($user['rol']);
            }
        }

        //User::factory()->count(100)->create();

    }
}
