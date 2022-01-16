#include <iostream>

#include "board.h"
#include "pieces.h"

#include "king.h"
#include "rook.h"
#include "queen.h"
#include "bishop.h"
#include "knight.h"
#include "pawn.h"


using namespace std;

void doMove (Board& board,
	     BoardPosition fromPos,
	     BoardPosition toPos)
{
  if (board.isOccupied(fromPos))
    {
      const Pieces* p = board.pieceAt(fromPos);
      if (p->canMoveTo(toPos, board))
      {
        board.move(fromPos, toPos);
        cout << fromPos << "-" << toPos << ": OK" << endl;
      }
      else
	  {
        cout << "Attempted illegal move: "
	         << fromPos << "-" << toPos << endl;
	  }
    }
  else
    {
      cout << "Attempted to move nonexistant piece: " << fromPos << "-" << toPos << endl;
    }
}




int main(int atgc, char** argv)
{
  Board board;

  board.addPiece(Rook(true), BoardPosition('a', 1));
  board.addPiece(Knight(true), BoardPosition('b', 1));
  board.addPiece(Bishop(true), BoardPosition('c', 1));
  board.addPiece(Queen(true), BoardPosition('d', 1));
  board.addPiece(King(true), BoardPosition('e', 1));
  board.addPiece(Bishop(true), BoardPosition('f', 1));
  board.addPiece(Knight(true), BoardPosition('g', 1));
  board.addPiece(Rook(true), BoardPosition('h', 1));

  board.addPiece(Pawn(true), BoardPosition('a', 2));
  board.addPiece(Pawn(true), BoardPosition('b', 2));
  board.addPiece(Pawn(true), BoardPosition('c', 2));
  board.addPiece(Pawn(true), BoardPosition('d', 2));
  board.addPiece(Pawn(true), BoardPosition('e', 2));
  board.addPiece(Pawn(true), BoardPosition('f', 2));
  board.addPiece(Pawn(true), BoardPosition('g', 2));
  board.addPiece(Pawn(true), BoardPosition('h', 2));


  board.addPiece(Rook(false), BoardPosition('a', 8));
  board.addPiece(Knight(false), BoardPosition('b', 8));
  board.addPiece(Bishop(false), BoardPosition('c', 8));
  board.addPiece(Queen(false), BoardPosition('d', 8));
  board.addPiece(King(false), BoardPosition('e', 8));
  board.addPiece(Bishop(false), BoardPosition('f', 8));
  board.addPiece(Knight(false), BoardPosition('g', 8));
  board.addPiece(Rook(false), BoardPosition('h', 8));

  board.addPiece(Pawn(false), BoardPosition('a', 7));
  board.addPiece(Pawn(false), BoardPosition('b', 7));
  board.addPiece(Pawn(false), BoardPosition('c', 7));
  board.addPiece(Pawn(false), BoardPosition('d', 7));
  board.addPiece(Pawn(false), BoardPosition('e', 7));
  board.addPiece(Pawn(false), BoardPosition('f', 7));
  board.addPiece(Pawn(false), BoardPosition('g', 7));
  board.addPiece(Pawn(false), BoardPosition('h', 7));



  char col, c;
  int row;

  cout << "Chess Game Transcript" << endl;

  while (cin >> col)
    {
      if (col >= 'a' && col <= 'h')
	{
	  cin >> row;
	  BoardPosition fromPos (col, row);
	  board.displayMoves(cout, fromPos);//fix this
	  cin >> c >> col >> row;
	  BoardPosition toPos (col, row);
	  doMove (board, fromPos, toPos);
	}
    }
  cout << board << endl;
  return 0;
}
