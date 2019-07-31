<div id="menu-central-inf" style="background-color: white; z-index: 4; border-top: 7px solid #BEBFC2;">
    <div class="menu-inf" style="position: relative; height: 30px; margin-top: 40px; ">
        <ul>
            {if $session == 'si'}
                <li id='menu_recomendados'> <a id='link_productos_recomendados'><span>Recomendados para ti</span></a></li>
                {else}
                <li id='menu_recomendados'> <a id='link_productos_recomendados'><span>Recomendados</span></a></li>
                {/if}
            <li class="cotiza"> <a href="/cotizador"><span>Cotizador</span></a></li>
            <li id='menu_interes'> <a id='link_productos_interes'><span>Productos extras</span></a></li>
        </ul>
    </div>
</div>
<div id="menu-central-oculto" style="background-color: white; z-index: 1;border-top: 7px solid #BEBFC2; display: none;">
    <div class="menu-inf2" style="position: relative; height: 30px; margin-top: 40px;">

    </div>
</div>




