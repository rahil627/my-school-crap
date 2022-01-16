#include "board.h"
#include <algorithm>

using namespace std;

Board::Board()
{
  _board = new Pieces*[64];
  fill_n(_board, 64, (Pieces*)0);
}


Board::Board(const Board& other)
{
  _board = new Pieces*[64];
  for (int i = 0; i < 64; ++i)
    if (other._board[i] != 0)
      _board[i] = other._board[i]->clone();
    else
      _board[i] = 0;
}


Board::~Board()
{
  for (int i = 0; i < 64; ++i)
    delete _board[i];
  delete [] _board;
}



const Board& Board::operator= (const Board& other)
{
  if (_board != other._board)
    {
      for (int i = 0; i < 64; ++i)
	{
	  delete _board[i];
	  if (other._board[i] != 0)
	    _board[i] = other._board[i]->clone();
	  else
	    _board[i] = 0;
	}
    }
  return *this;
}
      




void Board::addPiece  (const Pieces& piece, BoardPosition pos)
{
  int i = index(pos);
  delete _board[i];
  _board[i] = piece.clone();
  _board[i]->setPosition(pos);
}


bool Board::isOccupied(BoardPosition pos) const
{
  char c = pos.getColumn();
  if (c < 'a' || c > 'h') return false;
  int r = pos.getRow();
  if (r < 1 || r > 8) return false;
  int i = index(pos);
  return (_board[i] != 0);
}

const Pieces* Board::pieceAt(BoardPosition pos) const
{
  char c = pos.getColumn();
  if (c < 'a' || c > 'h') return 0;
  int r = pos.getRow();
  if (r < 1 || r > 8) return 0;
  int i = index(pos);
  return _board[i];
}


void Board::move(BoardPosition fromPos, BoardPosition toPos)
{
  int from = index(fromPos);
  Pieces* pieceToMove = _board[from];
  if (pieceToMove != 0)
    {
      if (pieceToMove->canMoveTo(toPos, *this))
	{
	  int to = index(toPos);
	  _board[from] = 0;
	  _board[to] = pieceToMove;
	  pieceToMove->moveTo(toPos);
	  
	  // Special case: castling of kings also moves a rook
	  if (((pieceToMove->symbol() == 'K') || (pieceToMove->symbol() == 'k'))
	      && (abs(fromPos.getColumn()
		      - toPos.getColumn()) > 1))
	    {
	      if (toPos.getColumn() == 'c')
		{
		  _board[index(BoardPosition('d', toPos.getRow()))]
		    = _board[index(BoardPosition('a', toPos.getRow()))];
		  _board[index(BoardPosition('a', toPos.getRow()))] = 0;
		}
	      else
		{
		  _board[index(BoardPosition('f', toPos.getRow()))]
		    = _board[index(BoardPosition('h', toPos.getRow()))];
		  _board[index(BoardPosition('h', toPos.getRow()))] = 0;
		}
	    }
	}
      else
	{
	  cerr << "Illegal move: " << fromPos 
	       << "-" <<  toPos << endl;
	}
    }
  else
    {
      cerr << "No piece to move: " << fromPos
	   << "-" << toPos << endl;
    }	
}



int Board::index(BoardPosition pos) const
{
  return (pos.getColumn() - 'a') * 8 + pos.getRow() - 1;
}
