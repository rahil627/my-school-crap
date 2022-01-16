#include "king.h"
#include "board.h"

using namespace std;



bool King::canMoveTo (BoardPosition newPosition,
		      const Board& onTheBoard) const
{
  if (onTheBoard.isOccupied(newPosition) &&
      onTheBoard.pieceAt(newPosition)->isWhite() == isWhite())
    {
      return false; // can't capture your own piece
    }

  BoardPosition oldPosition = getPosition();
  int columnDifference = oldPosition.getColumn() - newPosition.getColumn();
  int rowDifference = oldPosition.getRow() - newPosition.getRow();
  if ((abs(columnDifference) <= 1) && (abs(rowDifference) <= 1))return true;

  if ((rowDifference == 0) && (abs(columnDifference) == 2)) {
    // Check for castling
    if (hasMoved())
      return false;
    BoardPosition rookPosition = (columnDifference > 0)
      ? BoardPosition('a', oldPosition.getRow())
      : BoardPosition('h', oldPosition.getRow());
    if ((!onTheBoard.isOccupied(rookPosition))
	|| (onTheBoard.pieceAt(rookPosition)->hasMoved()))
      return false;
    BoardPosition intervening = oldPosition.left(columnDifference/2);
    if (onTheBoard.isOccupied(newPosition))
      return false;
    if (onTheBoard.isOccupied(intervening))
      return false;
    for (int row = 8; row > 0; --row) {
      for (char col = 'a'; col <= 'h'; col += 1) {
	const Pieces* p2
	  = onTheBoard.pieceAt(BoardPosition(col, row));
	if (p2 != 0 &&
	    p2->isWhite() != isWhite() &&
	    (p2->canMoveTo(intervening, onTheBoard)
	     || p2->canMoveTo(oldPosition, onTheBoard)
	     || p2->canMoveTo(newPosition, onTheBoard)))
	  return false;
      }
    }
    return true;
  }
  return false;
}




char King::symbol () const
  // returns the character representing the piece, uppercase if white,
  //   lowercase if black
{
  return (isWhite()) ? 'K' : 'k';
}


Pieces* King::clone () const
  // returns a pointer to an exact copy of the piece
{
  return new King(*this);
}
