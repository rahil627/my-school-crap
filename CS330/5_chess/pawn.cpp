#include "pawn.h"
#include "board.h"

using namespace std;



bool Pawn::canMoveTo (BoardPosition newPosition, const Board& onTheBoard) const
{
  //can't capture your own piece
  if(onTheBoard.isOccupied(newPosition) && onTheBoard.pieceAt(newPosition)->isWhite() == isWhite())return false;

  //common vars
  BoardPosition oldPosition = getPosition();
  int columnDifference = oldPosition.getColumn() - newPosition.getColumn();//horizontal
  int rowDifference = oldPosition.getRow() - newPosition.getRow();//vertical

  //movement
  if(onTheBoard.isOccupied(newPosition)==false)
  {
    //normal move
    if(onTheBoard.pieceAt(oldPosition)->isWhite()==true  && columnDifference==0 && rowDifference==-1)return true;
    if(onTheBoard.pieceAt(oldPosition)->isWhite()==false && columnDifference==0 && rowDifference== 1)return true;

    //special first move
    if(onTheBoard.isOccupied(BoardPosition(newPosition.getColumn(),newPosition.getRow()-1))==false && onTheBoard.pieceAt(oldPosition)->isWhite()==true  && oldPosition.getRow()==2 && columnDifference==0 && rowDifference==-2)return true;
    if(onTheBoard.isOccupied(BoardPosition(newPosition.getColumn(),newPosition.getRow()+1))==false && onTheBoard.pieceAt(oldPosition)->isWhite()==false && oldPosition.getRow()==7 && columnDifference==0 && rowDifference== 2)return true;
  }

  //capture
  if(onTheBoard.isOccupied(newPosition)==true)
  {
    if(onTheBoard.pieceAt(oldPosition)->isWhite()==true  && onTheBoard.pieceAt(newPosition)->isWhite()==false && abs(columnDifference)==1 && rowDifference==-1)return true;
    if(onTheBoard.pieceAt(oldPosition)->isWhite()==false && onTheBoard.pieceAt(newPosition)->isWhite()==true  && abs(columnDifference)==1 && rowDifference== 1)return true;
  }

  //else
  return false;
}


char Pawn::symbol () const
  // returns the character representing the piece, uppercase if white,
  //   lowercase if black
{
  return (isWhite()) ? 'P' : 'p';
}


Pieces* Pawn::clone () const
  // returns a pointer to an exact copy of the piece
{
  return new Pawn(*this);
}
