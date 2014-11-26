// Generated by Bisonc++ V4.01.00 on Sun, 08 Dec 2013 14:22:37 +0000

#ifndef ParserBase_h_included
#define ParserBase_h_included

#include <vector>
#include <iostream>

// $insert preincludes
#include "parsetype.h"

namespace // anonymous
{
    struct PI__;
}



class ParserBase
{
    public:
// $insert tokens

    // Symbolic tokens:
    enum Tokens__
    {
        TEST_EOF = 257,
        TEST_MATCH,
        TEST_UNIQUE,
        TEST_INARRAY,
        CMP_LT,
        CMP_GT,
        CMP_LE,
        CMP_GE,
        CMP_EQ,
        CMP_NE,
        CMD_SPACE,
        CMD_NEWLINE,
        CMD_EOF,
        CMD_INT,
        CMD_FLOAT,
        CMD_STRING,
        CMD_REGEX,
        CMD_ASSERT,
        CMD_UNSET,
        CMD_REP,
        CMD_WHILE,
        CMD_REPI,
        CMD_WHILEI,
        CMD_IF,
        CMD_ELSE,
        CMD_END,
        VARNAME,
        INTEGER,
        FLOAT,
        STRING,
        OPT_FIXED,
        OPT_SCIENTIFIC,
        LOGIC_AND,
        LOGIC_OR,
    };

// $insert LTYPE
    struct LTYPE__
    {
        int timestamp;
        int first_line;
        int first_column;
        int last_line;
        int last_column;
        char *text;
    };
// $insert STYPE
typedef parse_t STYPE__;


    private:
        int d_stackIdx__;
        std::vector<size_t>   d_stateStack__;
        std::vector<STYPE__>  d_valueStack__;
// $insert LTYPEstack
        std::vector<LTYPE__>      d_locationStack__;

    protected:
        enum Return__
        {
            PARSE_ACCEPT__ = 0,   // values used as parse()'s return values
            PARSE_ABORT__  = 1
        };
        enum ErrorRecovery__
        {
            DEFAULT_RECOVERY_MODE__,
            UNEXPECTED_TOKEN__,
        };
        bool        d_debug__;
        size_t      d_nErrors__;
        size_t      d_requiredTokens__;
        size_t      d_acceptedTokens__;
        int         d_token__;
        int         d_nextToken__;
        size_t      d_state__;
        STYPE__    *d_vsp__;
        STYPE__     d_val__;
        STYPE__     d_nextVal__;
// $insert LTYPEdata
         LTYPE__   d_loc__;
         LTYPE__  *d_lsp__;

        ParserBase();

        void ABORT() const;
        void ACCEPT() const;
        void ERROR() const;
        void clearin();
        bool debug() const;
        void pop__(size_t count = 1);
        void push__(size_t nextState);
        void popToken__();
        void pushToken__(int token);
        void reduce__(PI__ const &productionInfo);
        void errorVerbose__();
        size_t top__() const;

    public:
        void setDebug(bool mode);
}; 

inline bool ParserBase::debug() const
{
    return d_debug__;
}

inline void ParserBase::setDebug(bool mode)
{
    d_debug__ = mode;
}

inline void ParserBase::ABORT() const
{
    throw PARSE_ABORT__;
}

inline void ParserBase::ACCEPT() const
{
    throw PARSE_ACCEPT__;
}

inline void ParserBase::ERROR() const
{
    throw UNEXPECTED_TOKEN__;
}


// As a convenience, when including ParserBase.h its symbols are available as
// symbols in the class Parser, too.
#define Parser ParserBase


#endif


