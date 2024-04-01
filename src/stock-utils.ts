const CARD_WIDTH = 200 //also change in scss
const CARD_HEIGHT = 200

function getBackgroundInlineStyleForNimaliaCard(card: NimaliaCard) {
	let file
	switch (card.type) {
		case 1:
			file = 'biomeCards.png'
			break
	}

	const imagePosition = card.type_arg - 1
	const row = Math.floor(imagePosition / IMAGE_ITEMS_PER_ROW)
	const xBackgroundPercent = (imagePosition - row * IMAGE_ITEMS_PER_ROW) * 100
	const yBackgroundPercent = row * 100
	return `background-image: url('${g_gamethemeurl}img/${file}'); background-position: -${xBackgroundPercent}% -${yBackgroundPercent}%; background-size:1000%;`
}

function getBackgroundInlineStyleForGoalCard(card: Goal) {
	const file = 'goals.png'
	const imagePosition = card.id - 1
	const row = Math.floor(imagePosition / IMAGE_GOALS_PER_ROW)
	const xBackgroundPercent = (imagePosition - row * IMAGE_GOALS_PER_ROW) * 100
	const yBackgroundPercent = row * 100
	return `background-image: url('${g_gamethemeurl}img/${file}'); background-position: -${xBackgroundPercent}% -${yBackgroundPercent}%; background-size:1100%;`
}

