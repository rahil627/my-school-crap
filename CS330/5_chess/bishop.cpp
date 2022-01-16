#include "bishop.h"
#include "board.h"

using namespace std;



bool Bishop::canMoveTo (BoardPosition newPosition,
		      const Board& onTheBoard) const
{
  if (onTheBoard.isOccupied(newPosition) &&
      onTheBoard.pieceAt(newPosition)->isWhite() == isWhite())
    {
      return false; // can't capture your own piece
    }

  //common vars
  BoardPosition oldPosition = getPosition();
  int columnDifference = oldPosition.getColumn() - newPosition.getColumn();
  int rowDifference = oldPosition.getRow() - newPosition.getRow();

  //movement & capture
  if((abs(columnDifference) <= 7) && abs(columnDifference)==abs(rowDifference))//&& (abs(rowDifference) <= 7)
  {
    //check if there is a piece inbetween the bishop and it's new position

    //temp variables used in a loop to check each space inbetween the bishop and new position
    int tcol=oldPosition.getColumn();
    int trow=oldPosition.getRow();

    //forward-left
    if(columnDifference>0 && rowDifference<0)
    {
        tcol--;//move toward 0, once
        trow++;
        while(trow<newPosition.getRow())
        {
            if (onTheBoard.isOccupied(BoardPosition(tcol,trow))==true){return false;}
            tcol--;//step closer to 0
            trow++;
        }
    }
    //forward-right
    if(columnDifference<0 && rowDifference<0)
    {
        tcol++;
        trow++;
        while(trow<newPosition.getRow())
        {
            if (onTheBoard.isOccupied(BoardPosition(tcol,trow))==true){return false;}
            tcol++;
            trow++;
        }
    }
        //backward-left
    if(columnDifference>0 && rowDifference>0)
    {
        tcol--;
        trow--;
        while(trow<newPosition.getRow())
        {
            if (onTheBoard.isOccupied(BoardPosition(tcol,trow))==true){return false;}
            tcol--;
            trow--;
        }
    }
        //backward-right
    if(columnDifference<0 && rowDifference<0)
    {
        tcol++;
        trow++;
        while(trow<newPosition.getRow())
        {
            if (onTheBoard.isOccupied(BoardPosition(tcol,trow))==true){return false;}
            tcol++;
            trow++;
        }
    }
    return true;//finally!
 }
  return false;
}




char Bishop::symbol () const
  // returns the character representing the piece, uppercase if white,
  //   lowercase if black
{
  return (isWhite()) ? 'B' : 'b';
}


Pieces* Bishop::clone () const
  // returns a pointer to an exact copy of the piece
{
  return new Bishop(*this);
}
