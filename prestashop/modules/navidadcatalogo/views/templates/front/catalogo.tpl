<!doctype html>
    <html lang="{$language.iso_code}">

    <head>
      {block name='head'}
        {include file='_partials/head.tpl'}
      {/block}
    </head>

    <body id="{$page.page_name}" class="{$page.body_classes|classnames}">

      {hook h='displayAfterBodyOpeningTag'}

      <main>

        <header data-html2canvas-ignore id="header">
          {block name='header'}
            {include file='_partials/header.tpl'}
          {/block}
        </header>
        <aside id="notifications">
            <div class="container">
            </div>
        </aside>
        <section id="top_column">
          <div class="container">


            
            {block name="content"}
              <div class="book-wrapper" style="padding-top: 17px;">
                <div class="book-container">
                  
                  <div class="book" id="book">
                    <div class="hard page">
                      <img src="/modules/navidadcatalogo/views/images/catalogo-navidad-2-2.jpg" alt=""/>
                    </div>
                    <div class="page">
                      <img src="/modules/navidadcatalogo/views/images/catalogo-navidad-2-2.jpg" alt=""/>
                    </div>
                    <div class="page">
                      <img src="/modules/navidadcatalogo/views/images/catalogo-navidad-2-3.jpg" alt=""/>
                    </div>
                    <div class="page">
                      <img src="/modules/navidadcatalogo/views/images/catalogo-navidad-2-4.jpg" alt=""/>
                    </div>
                    <div class="page">
                      <img src="/modules/navidadcatalogo/views/images/catalogo-navidad-2-5.jpg" alt=""/>
                    </div>
                    <div class="page">
                      <img src="/modules/navidadcatalogo/views/images/catalogo-navidad-2-6.jpg" alt=""/>
                    </div>
                    <div class="page">
                      <img src="/modules/navidadcatalogo/views/images/catalogo-navidad-2-7.jpg" alt=""/>
                    </div>
                  </div>
                  <nav class="pull-right" aria-label="Page navigation example">
                    <ul class="pagination ">
                      <li class="page-item prev_page"><a class="page-link" href="#">&laquo;</a></li>
                      <li class="page-item next_page"><a class="page-link" href="#">&raquo;</a></li>
                    </ul>
                  </nav>
                </div>
              </div>
            {/block}

          </div>
        </section>

        <footer data-html2canvas-ignore id="footer">
          {block name="footer"}
            {include file="_partials/footer.tpl"}
          {/block}
        </footer>

      </main>

      {hook h='displayBeforeBodyClosingTag'}

      {block name='javascript_bottom'}
        {include file="_partials/javascript.tpl" javascript=$javascript.bottom}
      {/block}

    </body>

    </html>



