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
  let locationsCarousel = document.querySelectorAll(
    ".locations .locations-cards__carousel",
  );

  if (locationsCarousel.length > 0) {
    locationsCarousel.forEach((carousel) => {
      let splideElement = carousel.querySelector(".splide");
      if (splideElement) {
        loadSplide(() => {
          let locationsCarouselInstance = new Splide(splideElement, {
            type: "loop",
            perMove: 1,
            perPage: 1,
            arrows: true,
            pagination: false,
            accessibility: true,
            slideFocus: false,
            mediaQuery: "min",
            breakpoints: {
              [window.themeBreakpoints?.tablet ?? 768]: {
                perPage: splideElement.closest(".sidebar") ? 1 : 2,
              },
              [window.themeBreakpoints?.ldpi ?? 1024]: {
                perPage: splideElement.closest(".sidebar") ? 1 : 4,
              },
            },
          });
          locationsCarouselInstance.mount();
        });
      }
    });
  }
})();
