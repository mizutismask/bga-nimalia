// <reference path="../card-manager.ts"/>
class CardsManager extends CardManager<NimaliaCard> {
	constructor(public game: NimaliaGame) {
		super(game, {
			animationManager: game.animationManager,
			getId: (card) => `nimalia-card-${card.id}`,
			setupDiv: (card: NimaliaCard, div: HTMLElement) => {
				div.classList.add('nimalia-card')
				div.dataset.cardId = '' + card.id
				div.dataset.cardType = '' + card.type
				div.style.position = 'relative'
				/*div.style.width = '200px';
                div.style.height = '200px';
                */
				this.addRotateButton(card, div as HTMLDivElement, 'left')
				this.addRotateButton(card, div as HTMLDivElement, 'right')
			},
			setupFrontDiv: (card: NimaliaCard, div: HTMLElement) => {
				console.log('setupFrontDiv', card.type_arg)
				this.setFrontBackground(div as HTMLDivElement, card.type_arg)
				//this.setDivAsCard(div as HTMLDivElement, card.type);
				div.id = `${super.getId(card)}-front`
				div.classList.add('nml-card-order-100')
				div.dataset.rotation = '0'
			},
			setupBackDiv: (card: NimaliaCard, div: HTMLElement) => {
				div.style.backgroundImage = `url('${g_gamethemeurl}img/nimalia-card-background.jpg')`
			}
		})
	}

	private addRotateButton(card: NimaliaCard, cardDiv: HTMLDivElement, direction: 'left' | 'right') {
		const rotate: HTMLDivElement = document.createElement('div')
		rotate.id = `${super.getId(card)}-rotate-${direction}`
		rotate.classList.add(
			'fa',
			'fa-solid',
			`fa-rotate-${direction}`,
			`nml-rotate-${direction}`,
			'nml-rotate',
			'fa6-2xl'
		)
		cardDiv.appendChild(rotate)

		dojo.connect(rotate, 'click', this, (evt) => {
			const frontDiv = document.querySelector(`#${cardDiv.id} .front`) as HTMLElement
			const rotation =
				(parseInt(frontDiv.dataset.rotation) + ((direction === 'right' ? 90 : -90) % 360) + 360) % 360
			frontDiv.dataset.rotation = rotation.toString()
		})
	}

	public getCardName(cardTypeId: number) {
		return 'todo'
	}

	public getTooltip(card: NimaliaCard, cardUniqueId: number) {
		let tooltip = `
		<div class="xpd-city-zoom-wrapper">
			<div id="xpd-city-${cardUniqueId}-zoom" class="xpd-city-zoom" style="${getBackgroundInlineStyleForNimaliaCard(
			card
		)}"></div>
			<div class="xpd-city-zoom-desc-wrapper">
				<div class="xpd-city">${dojo.string.substitute(_('${to}'), {
					to: 'replace'
				})}</div>
			</div>
		</div>`
		return tooltip
	}

	private setFrontBackground(cardDiv: HTMLDivElement, cardType: number) {
		const destinationsUrl = `${g_gamethemeurl}img/biomeCards.png`
		cardDiv.style.backgroundImage = `url('${destinationsUrl}')`
		const imagePosition = cardType - 1
		const row = Math.floor(imagePosition / IMAGE_ITEMS_PER_ROW)
		const xBackgroundPercent = (imagePosition - row * IMAGE_ITEMS_PER_ROW) * 100
		const yBackgroundPercent = row * 100
		cardDiv.style.backgroundPositionX = `-${xBackgroundPercent}%`
		cardDiv.style.backgroundPositionY = `-${yBackgroundPercent}%`
		cardDiv.style.backgroundSize = `1000%`
	}
}
