#ifndef PIECES
#define PIECES


#include <iostream>
#include "boardposition.h"

class Board;


class Pieces
{
private:
  bool _colorIsWhite;
  bool _hasMoved;
  BoardPosition _position;

public:

  Pieces (bool isWhite)
    : _colorIsWhite(isWhite), _hasMoved(false)
  { }
  
  Pieces (bool isWhite, BoardPosition atPos)
    : _colorIsWhite(isWhite), _hasMoved(false), _position(atPos)
  { }
  
  
  bool isWhite()  const   {return _colorIsWhite;}
  bool hasMoved() const   {return _hasMoved;}
  
  BoardPosition getPosition() const   {return _position;}
  void setPosition(BoardPosition pos) {_position = pos;}
  
  void moveTo (BoardPosition newPosition);


  // The following functions must be implemented in the subclasses
  // because the appropriate  actions depend upon exactly what kind
  // of piece it is.

  virtual bool canMoveTo (BoardPosition newPosition,
			  const Board& onTheBoard) const = 0;


  virtual char symbol () const = 0;
  // returns the character representing the piece, uppercase if white,
  //   lowercase if black

  virtual Pieces* clone () const = 0;
  // returns a pointer to an exact copy of the piece
  //   (implemented as new SubClassName(*this) )
};

std::ostream& operator<< (std::ostream& out, const Pieces&);

#endif

