#include "knight.h"
#include "board.h"

using namespace std;



bool Knight::canMoveTo (BoardPosition newPosition,
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

  return ((abs(rowDifference) == 1 && abs(columnDifference) == 2)
	  || (abs(rowDifference) == 2 && abs(columnDifference) == 1));
}




char Knight::symbol () const
  // returns the character representing the piece, uppercase if white,
  //   lowercase if black
{
  return (isWhite()) ? 'N' : 'n';
}


Pieces* Knight::clone () const
  // returns a pointer to an exact copy of the piece
{
  return new Knight(*this);
}
