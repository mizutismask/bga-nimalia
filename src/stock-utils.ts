const CARD_WIDTH = 200 //also change in scss
const CARD_HEIGHT = 200

function getBackgroundInlineStyleForNimaliaCard(card: NimaliaCard) {
	let file
	switch (card.type) {
		case 1:
			file = 'nimaliacards.png'
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

const GOALS_DESC = [
	'1 point per identical animal if at least 2 of them are orthogonally adjacent to an otter',
	'2 points per otter whose river connects to a lake.',
	'2 points per distinct rain forest area',
	'2 points per gorilla orthogonally adjacent to a lake',
	'2 points per space of your largest savannah.',
	'6 points per savanna area spanning exactly 4 spaces.',
	'4 points per 2×2 square of ice floe (a space can be part of several squares)',
	'2 points per polar bear that is part of a group of bears, and - 1 per solitary bear',
	'2 points per space of the terrain of which you have the fewest spaces in your reserve (here it’s 2×3 = 6)',
	'3, 5, 8, 13 or 21 points if your reserve completely fills a 2x2, 3x3, 4x4, 5x5, or 6x6 square',
	'3 points per row of your reserve that contains all 4 terrain types',
	'3 points per strict horizontally adjacent pair of identical animals. 3 animals don’t count',
	'From 0 to 8 points according to how few giraffes you have (0 giraffe = 8 )',
	'3 points per flamingo that is not touching the edge of your reserve',
	'From 0 to 15 points according to how long your longest river runs. For example, a 3-space river is worth 0, +1, +2(thus 3)',
	'The player with the longest river gets 5 points, 2nd gets 2 points',
	'3 points per panda that is touching the edge of your reserve',
	'The player with the most gorillas gets 5 points (2nd gets 2 points). The player with the most pandas gets - 5 points (2nd gets - 2 points)',
	'3 points per column of your reserve that contains exactly 1 penguin',
	'The player with the fewest lions gets 3 points. Everyone else gets - 2 points',
	'The player with the most crocodiles gets 5 points (2nd gets 2 points ). The player with the fewest flamingoes gets 5 points (2nd gets 2 points)',
	'2 points per crocodile orthogonally adjacent to at least one giraffe'
]
