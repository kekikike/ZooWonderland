<?php
declare (strict_types=1);
function iniciarSesionSegura(): void {
 ini_set('session.cookie_httponly', '1'); 
 ini_set('session.cookie_secure', '1');   
    ini_set('session.use_strict_mode', '1');
    ini_set('session.gc_maxlifetime', '1300');
    session_start();
}
?>