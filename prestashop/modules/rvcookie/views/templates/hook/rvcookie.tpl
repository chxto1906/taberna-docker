{*
*  @author    RV Templates
*  @copyright 2017-2018 RV Templates. All Rights Reserved.
*  @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*}

<script type="text/javascript">
 /* <![CDATA[ */
        function setcookie()
		{
            var cookiename = 'cookie_law';
            var cookievalue = '1';
            var expire = new Date();
            expire.setMonth(expire.getMonth()+12);
            document.cookie = cookiename + "=" + escape(cookievalue) +";path=/;" + ((expire==null)?"" : ("; expires=" + expire.toGMTString()))
        }
        function closeNotify()
		{
			$('#rv_cookie').slideUp();
			setcookie();
        }
	  /* ]]> */
</script>

<div id="rv_cookie" class="cookie-notice" >
    <div class="cookie-inner container">
       <div class="cookie-content">
        {$rvcookie.text nofilter}
          <button id="allowcookie" class="btn btn-primary" onclick="closeNotify()">
            <span>{l s='Allow' mod='rvcookie'}</span>
        </button>
      </div>
  </div>
</div>