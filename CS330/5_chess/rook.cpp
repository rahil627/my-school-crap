#include "rook.h"
#include "board.h"

using namespace std;



bool Rook::canMoveTo (BoardPosition newPosition, const Board& onTheBoard) const
{
  //can't capture your own piece
  if(onTheBoard.isOccupied(newPosition) && onTheBoard.pieceAt(newPosition)->isWhite() == isWhite())return false;

  //common vars
  BoardPosition oldPosition = getPosition();
  int columnDifference = oldPosition.getColumn() - newPosition.getColumn();//horizontal
  int rowDifference = oldPosition.getRow() - newPosition.getRow();//vertical

  if((abs(columnDifference)<=7 && rowDifference==0)||(columnDifference==0 && (abs(rowDifference)<=7)))
  {
    //check if there is a piece inbetween the rook and it's new position

    //temp variables used in a loop to check each space inbetween the bishop and new position
    int tcol=oldPosition.getColumn();
    int trow=oldPosition.getRow();

    //left
    if(columnDifference>0)//backwards
    {
        tcol--;//move temp closer to new position once
        while(tcol>newPosition.getColumn())//check from old position to new position
        {
            if(onTheBoard.isOccupied(BoardPosition(tcol,oldPosition.getRow()))==true)return false;
            tcol--;
        }
    }
    //right
    if(columnDifference<0)
    {
        tcol++;
        while(tcol<newPosition.getColumn())
        {
            if(onTheBoard.isOccupied(BoardPosition(tcol,oldPosition.getRow()))==true){return false;}
            tcol++;
        }
    }
    //down
    if(rowDifference>0)//difference is backwards
    {
        trow--;
        while(trow>newPosition.getRow())
        {
            if(onTheBoard.isOccupied(BoardPosition(oldPosition.getColumn(),trow))==true)return false;
            trow--;
        }
    }
    //up
    if(rowDifference<0)
    {
        trow++;
        while(trow<newPosition.getRow())
        {
            if(onTheBoard.isOccupied(BoardPosition(oldPosition.getColumn(),trow))==true)return false;
            trow++;
        }
    }
    return true;//finally!
  }
  return false;
}




char Rook::symbol () const
  // returns the character representing the piece, uppercase if white,
  //   lowercase if black
{
  return (isWhite()) ? 'R' : 'r';
}


Pieces* Rook::clone () const
  // returns a pointer to an exact copy of the piece
{
  return new Rook(*this);
}
