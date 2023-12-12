function addTemporaryClass(element: HTMLElement | string, className: string, removalDelay: number) {
	dojo.addClass(element, className)
	setTimeout(() => dojo.removeClass(element, className), removalDelay)
}

function removeClass(className: string, rootNode?: HTMLElement | Document): void {
	if (!rootNode) rootNode = document
	else rootNode = rootNode as HTMLElement
	rootNode.querySelectorAll('.' + className).forEach((item) => item.classList.remove(className))
}