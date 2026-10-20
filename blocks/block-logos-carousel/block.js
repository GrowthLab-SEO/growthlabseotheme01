if (!window.loadSplide) {
  window.loadSplide = (callback) => {
    if (typeof Splide !== "undefined") return callback();
    if (window.__splideLoading) {
      window.__splideCallbacks = window.__splideCallbacks || [];
      window.__splideCallbacks.push(callback);
      return;
    }
    window.__splideLoading = true;
    // Keep callbacks left pending by a failed attempt so they run once Splide loads
    window.__splideCallbacks = window.__splideCallbacks || [];
    window.__splideCallbacks.push(callback);
    // On failure, reset the loading flag so a later call can retry
    const fail = (message) => {
      console.error(message);
      window.__splideLoading = false;
    };
    if (!window.splideData || !window.splideData.url) {
      fail("Splide data not available");
      return;
    }
    const script = document.createElement("script");
    script.src = splideData.url;
    script.onload = () => {
      const checkSplide = (attempts = 0) => {
        if (typeof Splide !== "undefined") {
          window.__splideCallbacks.forEach((fn) => fn());
          window.__splideCallbacks = [];
          return;
        }
        if (attempts < 10) {
          setTimeout(() => checkSplide(attempts + 1), 100);
        } else {
          fail("Splide failed to load after retries");
        }
      };
      checkSplide();
    };
    script.onerror = () => {
      script.remove();
      fail("Failed to load Splide script");
    };
    document.head.appendChild(script);
  };
}

(() => {
  const logosCarousels = document.querySelectorAll(
    ".logos-carousel__carousel .splide",
  );
  if (logosCarousels) {
    for (var i = 0; i < logosCarousels.length; i++) {
      const splideEl = logosCarousels[i];
      const slidesCount = splideEl.querySelectorAll('.splide__slide').length;
      const isSidebar = !!splideEl.closest('.sidebar');

      // determine perPage values for each breakpoint
      const perPageBase = 3;
      const perPageTablet = isSidebar ? 3 : 5;
      const perPageLdpi = isSidebar ? 3 : 7;

      // ensure perPage never exceeds the number of slides
      const effectiveBase = Math.min(perPageBase, slidesCount);
      const effectiveTablet = Math.min(perPageTablet, slidesCount);
      const effectiveLdpi = Math.min(perPageLdpi, slidesCount);


      const maxPerPage = Math.max(effectiveBase, effectiveTablet, effectiveLdpi);

      loadSplide(() => {
        new Splide(splideEl, {
          type: slidesCount > maxPerPage ? 'loop' : 'slide',
          perPage: effectiveBase,
          perMove: effectiveBase,
          arrows: false,
          pagination: slidesCount > effectiveBase,
          accessibility: true,
          slideFocus: false,
          mediaQuery: "min",
          breakpoints: {
            [window.themeBreakpoints?.tablet ?? 768]: {
              perPage: effectiveTablet,
              perMove: effectiveTablet,
            },
            [window.themeBreakpoints?.ldpi ?? 1024]: {
              perPage: effectiveLdpi,
              perMove: effectiveLdpi,
            },
          },
        }).mount();
      });
    }
  }
})();
