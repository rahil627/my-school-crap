#ifndef BOARD
#define BOARD


#include <iostream>
#include "boardposition.h"
#include "pieces.h"


class Board
{
private:
  Pieces** _board; 

public:

  Board();

  Board(const Board&);
  ~Board();

  const Board& operator= (const Board&);

  /** Place a new piece on the board */
  void addPiece (const Pieces& piece, BoardPosition pos);

  /** Is there a piece on this square? */
  bool isOccupied(BoardPosition pos) const;

  /** Return the piece at a given position (or nll if there is no
      piece there. */
  const Pieces* pieceAt(BoardPosition pos) const;

  /** Move a piece from one position to another. If the moved
      piece lands on another, the other pice is captured (removed
      from the board. */
  void move(BoardPosition fromPos, BoardPosition toPos);


  /** Print the board, showing an '@' character in each position to which
      the piece currently at pos can move. */
  void displayMoves (std::ostream& out,
		     BoardPosition pos) const;



private:

  int index(BoardPosition pos) const;

};

/** Display the entire board */
inline
std::ostream& operator<< (std::ostream& out, const Board& board)
{
  board.displayMoves(out, BoardPosition('i',9));
  return out;
}

#endif
