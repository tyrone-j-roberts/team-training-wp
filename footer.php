        </main>
          <script>

            (function() {
                if (!window.ReactNativeWebView) return;

                var aTags = document.getElementsByTagName('a');

                for (var i = 0; i < aTags.length; i++) {
                  aTags[i].addEventListener('click', function(e) {
                    e.preventDefault();
                    var href = e.currentTarget.href;
                    window.ReactNativeWebView.postMessage(JSON.stringify({ type: 'LinkClick', href: href }));
                  });
                }

                window.addEventListener('load', function () {
                  window.ReactNativeWebView.postMessage(JSON.stringify({ type: 'Loaded', height: document.documentElement.scrollHeight }));
                });
            }())

            
          </script>
		<?php wp_footer(); ?>
    </body>
</html>