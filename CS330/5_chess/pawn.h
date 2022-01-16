#ifndef PAWN_H
#define PAWN_H


#include "pieces.h"

class Pawn: public Pieces
{
public:

  Pawn (bool isWhite): Pieces(isWhite)
  { }

  Pawn (bool isWhite, BoardPosition atPos)
    : Pieces(isWhite, atPos)
  { }


  // The following functions must be implemented in the subclasses
  // because the appropriate  actions depend upon exactly what kind
  // of piece it is.

  virtual bool canMoveTo (BoardPosition newPosition,
			  const Board& onTheBoard) const;


  virtual char symbol () const;
  // returns the character representing the piece, uppercase if white,
  //   lowercase if black

  virtual Pieces* clone () const;
  // returns a pointer to an exact copy of the piece
};

#endif
