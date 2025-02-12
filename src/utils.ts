function addTemporaryClass(element: HTMLElement | string, className: string, removalDelay: number) {
	dojo.addClass(element, className)
	setTimeout(() => dojo.removeClass(element, className), removalDelay)
}

function removeClass(className: string, rootNode?: HTMLElement | Document): void {
	if (!rootNode) rootNode = document
	else rootNode = rootNode as HTMLElement
	rootNode.querySelectorAll('.' + className).forEach((item) => item.classList.remove(className))
}

/*
 * Detect if spectator or replay
 */
function isReadOnly() {
	return this.isSpectator || typeof (this as any).g_replayFrom != 'undefined' || (this as any).g_archive_mode
}

function replaceAfterLastDash(inputString: string, replacement:string): string {
    return inputString.replace(/[^-]*$/, replacement);
}

function closeCurrentTooltip() {
	if (this.displayedTooltip == null) return
	else {
		this.displayedTooltip.close()
		this.displayedTooltip = null
	}
}

function addTooltipOnClickHelpButton(id, html, delay) {
	let tooltip = new dijit.Tooltip({
		label: html,
		showDelay: delay
	})

	dojo.connect($(id), 'click', (evt) => {
		evt.stopPropagation()

		if (tooltip.state == 'SHOWING') {
			this.closeCurrentTooltip()
		} else {
			this.closeCurrentTooltip()
			tooltip.open($(id))
			this.displayedTooltip = tooltip
		}
	})

	dojo.connect($(id), 'mouseleave', () => {
		tooltip.close()
	})
}
