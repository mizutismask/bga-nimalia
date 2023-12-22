/**
 * Player table.
 */
class PlayerTable {
	private handStock: LineStock<NimaliaCard>

	constructor(private game: NimaliaGame, player: NimaliaPlayer) {
		const isMyTable = player.id === game.getPlayerId().toString()
		const ownClass = isMyTable ? 'own' : ''
		let html = `
			<div id="player-table-${player.id}" class="player-order${player.playerNo} player-table ${ownClass}">
				<a id="anchor-player-${player.id}"></a>
                <div id="reserve-${player.id}" class="nml-reserve"></div>
				<div class="nml-player-name">${player.name}</div>
            </div>
        `
		dojo.place(html, 'player-tables')
		this.setupReserve(player)

		if (isMyTable) {
			const handHtml = `
			<div id="hand-${player.id}" class="nml-player-hand"></div>
        `
			dojo.place(handHtml, `player-table-${player.id}`, 'first')
			this.initHand(player)
		}
	}

	private initHand(player: NimaliaPlayer) {
		const smallWidth = window.matchMedia('(max-width: 830px)').matches
		var baseSettings = {
			center: true,
			gap: '10px'
		}
		if (smallWidth) {
			baseSettings['direction'] = 'row' as 'row'
			baseSettings['wrap'] = 'nowrap' as 'nowrap'
		} else {
			baseSettings['direction'] = 'col' as 'col'
			baseSettings['wrap'] = 'wrap' as 'wrap'
		}

		//log('smallWidth', smallWidth, baseSettings)

		this.handStock = new LineStock<NimaliaCard>(this.game.cardsManager, $('hand-' + player.id), baseSettings)
		this.handStock.setSelectionMode('single')
		this.handStock.onSelectionChange = (selection: Array<NimaliaCard>, lastChange: NimaliaCard) => {
			dojo.toggleClass(`player-table-${player.id}`, "nml-card-selected", selection.length > 0);
		}
	}

	private setupReserve(player: NimaliaPlayer) {
		const divId = `reserve-${player.id}`
		for (let i = 0; i < 36; i++) {
			const squareId = `square-${player.id}-${i + 1}`
			dojo.place(
				`
            <div id="${squareId}" class="nml-square">
            `,
				divId
			)
			if (parseInt(player.id) === this.game.getPlayerId()) {
				dojo.connect($(squareId), 'drop', this, dojo.hitch(this, this.onCardDrop))
				dojo.connect($(squareId), 'dragover', this, dojo.hitch(this, this.onCardDropOver))
				dojo.connect($(squareId), 'click', this, dojo.hitch(this, this.onSquareClick))
			}
		}
	}

	public displayGrid(player: NimaliaPlayer, cards: Array<NimaliaCard>) {
		dojo.query(`#reserve-${player.id} .nml-square`).empty()
		cards.forEach((c) => {
			this.createCardInGrid(parseInt(player.id), c)
		})
	}

	public createCardInGrid(playerId: number, card: NimaliaCard): string {
		const divId = this.game.cardsManager.getId(card)
		dojo.create(
			'div',
			{
				id: this.game.cardsManager.getId(card),
				style: getBackgroundInlineStyleForNimaliaCard(card),
				class: 'nimalia-card card-side front nml-card-order-' + card.order,
				'data-rotation': card.rotation
			},
			`square-${playerId}-${card.location_arg}`
		)
		return divId
	}

	public removeCardFromGrid(card: NimaliaCard) {
		$(this.game.cardsManager.getId(card)).remove()
	}

	public replaceCardsInHand(cards: Array<NimaliaCard>) {
		log('replaceCardsInHand', cards)
		this.handStock.removeAll()
		this.handStock.addCards(cards)
		cards.forEach((c) => this.setupCardInHand(c))
		/*this.handStock.addCards([{
			"id": 20,
			"location": "hand",
			"location_arg": 2333092,
			"type": 1,
			"type_arg": 11,
			order:1,
			rotation:1,
		}])*/
	}
	public addCardsInHand(cards: Array<NimaliaCard>) {
		log('add cards', cards)
		this.handStock.addCards(cards)
		cards.forEach((c) => this.setupCardInHand(c))
	}

	private setupCardInHand(c: NimaliaCard) {
		const cardId = this.game.cardsManager.getId(c)
		dojo.attr(cardId, 'draggable', true)
		dojo.connect($(cardId), 'dragstart', this, dojo.hitch(this, this.onCardDragStart))
		dojo.connect($(cardId), 'touchmove', this, dojo.hitch(this, this.onCardDragStart))
	}

	private onCardDragStart(evt) {
		if (!(this.game as any).isCurrentPlayerActive() || this.game.clientActionData.placedCardId) {
			evt.dataTransfer.clearData()
			evt.preventDefault()
			evt.stopPropagation()
			return
		}
		// Add the target element's id to the data transfer object
		evt.dataTransfer?.setData('text/plain', evt.target.id) //we move the whole card
		//evt.dataTransfer.effectAllowed = 'move'
		//log('drag', evt.target.id)
	}

	private onCardDrop(evt) {
		// Add the target element's id to the data transfer object
		evt.dataTransfer.effectAllowed = 'move'
		evt.preventDefault()
		evt.stopPropagation()
		const cardId = evt.dataTransfer.getData('text/plain')
		const square = (evt.target as HTMLElement).closest('.nml-square')
		this.moveCardToGrid(cardId, square)
	}

	private onSquareClick(evt: MouseEvent) {
		if (
			!(this.game as any).isCurrentPlayerActive() ||
			this.game.clientActionData.placedCardId ||
			this.handStock.getSelection().length !== 1 ||
			!(evt.target as HTMLElement).classList.contains('dropzone')
		) {
			evt.preventDefault()
			evt.stopPropagation()
			return
		}
		this.moveCardToGrid(this.game.cardsManager.getId(this.handStock.getSelection()[0]), evt.target)
	}

	private moveCardToGrid(cardId: string, square) {
		log('drop', cardId, 'to', square.id)
		if (cardId && square) {
			this.game.clientActionData.previousCardParentInHand = $(cardId).parentElement
			square.appendChild($(cardId))
			$(cardId).classList.add('local-change')
			/*this.handStock.removeCard(
				this.handStock.getCards().filter((c) => c.id == (this.game as any).getPart(cardId, -1))[0]
			)*/
			this.game.clientActionData.destinationSquare = square.id
			this.game.clientActionData.placedCardId = cardId
			this.handStock.setSelectableCards([]) //disables all cards
		}
		dojo.toggleClass('place-card-button', 'disabled', !cardId || !square)
		dojo.toggleClass('cancel-button', 'disabled', !cardId || !square)
	}

	private onCardDropOver(evt) {
		evt.preventDefault()
		evt.stopPropagation()
		if (evt.target.classList && evt.target.classList.contains('dropzone')) {
			evt.dataTransfer.dropEffect = 'move'
		} else {
			evt.dataTransfer.dropEffect = 'none'
		}
	}

	public showMove(playerId: number, playedCard: NimaliaCard) {
		const myOwnMove = playerId == this.game.getPlayerId()
		log('show move', playerId, playedCard, myOwnMove)

		if (!myOwnMove || isReadOnly()) {
			const id = this.createCardInGrid(playerId, playedCard)
			removeClass('last-move')
			$(id).classList.add('last-move')
		} else {
			log('this.game.clientActionData', this.game.clientActionData)
			if (this.game.clientActionData.previousCardParentInHand) {
				this.cancelLocalMove()
				this.removeCardFromHand(playedCard.id)
				log('createCardInGrid', playedCard)
				this.createCardInGrid(playerId, playedCard)
				this.game.resetClientActionData()
			}
		}
	}

	private removeCardFromHand(placedCardId: number) {
		this.handStock.removeCard(this.handStock.getCards().filter((c) => c.id == placedCardId)[0])
	}

	/*private removeCardFromHand(placedCardId: string) {
		this.handStock.removeCard(
			this.handStock.getCards().filter((c) => c.id == (this.game as any).getPart(placedCardId, -1))[0]
		)
	}*/

	public cancelLocalMove() {
		this.handStock.setSelectableCards(this.handStock.getCards())
		if (
			this.game.clientActionData?.placedCardId &&
			$(this.game.clientActionData.placedCardId) &&
			this.game.clientActionData?.previousCardParentInHand
		) {
			log(
				'restore',
				this.game.clientActionData.placedCardId,
				'inside',
				this.game.clientActionData?.previousCardParentInHand.id
			)
			this.game.clientActionData.previousCardParentInHand.appendChild($(this.game.clientActionData.placedCardId))
			return true
		}
		return false
	}
}
