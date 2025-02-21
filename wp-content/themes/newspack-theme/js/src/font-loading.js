const fontsToLoad = window.newspackFontLoading?.fonts || [];
Promise.all(
	fontsToLoad.map(fontName => document.fonts.load(`1rem ${fontName}`))
).then(res => {
	if (res.length === fontsToLoad.length) {
		document.body.classList.remove('newspack--font-loading');
	}
});
