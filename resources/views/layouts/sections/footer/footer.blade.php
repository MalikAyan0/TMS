@php
$containerFooter =
isset($configData['contentLayout']) && $configData['contentLayout'] === 'compact'
? 'container-xxl'
: 'container-fluid';
@endphp

<!-- Footer-->
<footer class="content-footer footer bg-footer-theme">
  <div class="{{ $containerFooter }}">
    <div class="footer-container d-flex align-items-center justify-content-between py-4 flex-md-row flex-column">
      <div class="text-body">
        &#169;
        <script>
          document.write(new Date().getFullYear());
        </script>
        , made with ❤️ by <a href="#" target="_blank" class="footer-link">Huraira sarwar</a>
      </div>
      <div class="d-none d-lg-inline-block">
        <a href="#" class="footer-link me-4" target="_blank">License</a>
        <a href="#" target="_blank" class="footer-link me-4">Documentation</a>
        <a href="#" target="_blank" class="footer-link">Support</a>
      </div>
    </div>
  </div>
</footer>
<!-- / Footer -->
