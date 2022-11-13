        </main>
          <script>
            window.addEventListener('load', function () {
                window.ReactNativeWebView.postMessage(JSON.stringify({ type: 'Loaded', height: document.documentElement.scrollHeight }));
            });
          </script>
		<?php wp_footer(); ?>
    </body>
</html>