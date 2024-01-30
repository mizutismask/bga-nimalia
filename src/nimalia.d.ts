/**
 * Your game interfaces
 */
declare const define
declare const ebg
declare const $
declare const dojo: Dojo
declare const _
declare const g_gamethemeurl

// remove this if you don't use cards. If you do, make sure the types are correct . By default, some number are send as string, I suggest to cast to right type in PHP.
interface Card {
	id: number
	location: string
	location_arg: number
	type: number
	type_arg: number
}
interface NimaliaCard extends Card {
	order: number
	rotation: number
}

interface NimaliaPlayer extends Player {
	playerNo: number
}

interface Goal {
	id: number
	level: number
	color: number
}

interface NimaliaGamedatas {
	current_player_id: string
	decision: { decision_type: string }
	game_result_neutralized: string
	gamestate: Gamestate
	gamestates: { [gamestateId: number]: Gamestate }
	neutralized_player_id: string
	notifications: { last_packet_id: string; move_nbr: string }
	playerorder: (string | number)[]
	playerOrderWorkingWithSpectators: number[]
	players: { [playerId: number]: NimaliaPlayer }
	tablespeed: string
	lastTurn: boolean
	turnOrderClockwise: boolean
	// counters
	winners: number[]
	// Add here variables you set up in getAllDatas
	hand: Array<NimaliaCard>
	goals: Array<Goal>
	round: { round: number; clockwise: boolean; goals: Array<Goal> }
	grids: { [playerId: number]: Array<NimaliaCard> }
	scores: Array<NotifScoreArgs>
}

interface ClientActionData {
	placedCardId: string
	destinationSquare: string
	previousCardParentInHand: HTMLElement
}

interface NimaliaGame extends Game {
	shiftGrid(direction: string): void
	updateShiftGridButtons(): void
	cardsManager: CardsManager
	animationManager: AnimationManager
	getCurrentPlayer(): NimaliaPlayer
	clientActionData: ClientActionData
	resetClientActionData(): void
	getPlayerId(): number
	getPlayerScore(playerId: number): number
	setTooltip(id: string, html: string): void
	setTooltipToClass(className: string, html: string): void
}

interface EnteringChooseActionArgs {
	canPass: boolean
}

interface EnteringPlaceCardArgs {
	possibleSquares: { [playerId: number]: Array<String> }
	canShiftGrid: { [playerId: number]: { [direction: string]: Array<Boolean> } }
}

interface CardsMoveArgs {
	playerId: number
	added?: Array<NimaliaCard> //to hand
	playedCard?: NimaliaCard
	fromUndo?: boolean
	undoneCard?: NimaliaCard
}

interface GridMovedArgs {
	possibleSquares: string[]
	playerId: number
	cards: Array<NimaliaCard>
	canShiftGrid: { [direction: string]: Array<Boolean> }
}

interface NewRoundArgs {
	round: number
	clockwise: boolean
	goals: Array<Goal>
}

interface NotifPointsArgs {
	playerId: number
	points: number
	delta: number
	scoreType: string
}

interface NotifScoreArgs {
	playerId: number
	score: number
	scoreType: string
}

interface NotifWinnerArgs {
	playerId: number
}

interface NotifScorePointArgs {
	playerId: number
	points: number
}
